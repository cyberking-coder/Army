<?php

namespace App\Console\Commands;

use App\Models\Page;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

/**
 * Scrape the old jointerritorialarmy.com site for page content and
 * import it into the pages table. Intended as a one-shot migration
 * helper, not production use.
 *
 * Usage:
 *   php artisan legacy:scrape --url=https://www.jointerritorialarmy.com
 *   php artisan legacy:scrape --url=https://... --only=join-as-jawan
 *   php artisan legacy:scrape --dry-run
 *
 * The scraper reads the given URL(s), extracts the H1 and the main
 * content area (configurable via --selector), and upserts pages
 * keyed by slug.
 */
class ScrapeLegacySite extends Command
{
    protected $signature = 'legacy:scrape
        {--url= : Full URL of the page to scrape (default: homepage)}
        {--base= : Base site URL to resolve relative links}
        {--selector=main : CSS-ish selector of the content block}
        {--only=* : One or more paths relative to base to scrape}
        {--dry-run : Print extracted data without saving}';

    protected $description = 'Scrape the legacy site and upsert pages';

    public function handle(): int
    {
        $url  = $this->option('url');
        $base = rtrim($this->option('base') ?? $url ?? '', '/');
        $only = $this->option('only');
        $dry  = (bool) $this->option('dry-run');

        $targets = [];

        if (! empty($only)) {
            foreach ($only as $path) {
                $targets[] = $base.'/'.ltrim($path, '/');
            }
        } elseif ($url) {
            $targets[] = $url;
        } else {
            $this->error('Pass --url=... or --only=path1 --only=path2');
            return self::FAILURE;
        }

        $count = 0;
        foreach ($targets as $target) {
            $this->info("Fetching: $target");
            try {
                $response = Http::timeout(20)->withOptions(['verify' => false])->get($target);
                if (! $response->successful()) {
                    $this->warn("  HTTP {$response->status()} — skipped");
                    continue;
                }
                $html = $response->body();
                $data = $this->extract($html);

                if (! $data['title']) {
                    $this->warn("  No title found — skipped");
                    continue;
                }

                $slug = Str::slug(parse_url($target, PHP_URL_PATH) ?: $data['title']);

                if ($dry) {
                    $this->line("  → Would upsert: {$data['title']} ({$slug})");
                    $this->line('    Content length: '.strlen($data['content']).' chars');
                } else {
                    Page::updateOrCreate(
                        ['slug' => $slug],
                        [
                            'title'       => $data['title'],
                            'menu_name'   => $data['title'],
                            'description' => $data['content'],
                            'status'      => 'draft', // review before publishing
                        ]
                    );
                    $this->info("  → Imported as draft: $slug");
                }
                $count++;
            } catch (\Throwable $e) {
                $this->error("  Error: ".$e->getMessage());
            }
        }

        $this->line("");
        $this->info("Processed $count page(s).");
        if (! $dry) {
            $this->line("Open /admin/pages and review the drafts before publishing.");
        }

        return self::SUCCESS;
    }

    protected function extract(string $html): array
    {
        // Title: prefer <h1>, fall back to <title>
        $title = $this->firstMatch('/<h1[^>]*>(.*?)<\/h1>/is', $html)
            ?? $this->firstMatch('/<title[^>]*>(.*?)<\/title>/is', $html)
            ?? '';

        // Content: prefer <main>, else <article>, else <body>
        $content = $this->firstMatch('/<main[^>]*>(.*?)<\/main>/is', $html)
            ?? $this->firstMatch('/<article[^>]*>(.*?)<\/article>/is', $html)
            ?? $this->firstMatch('/<body[^>]*>(.*?)<\/body>/is', $html)
            ?? '';

        // Strip scripts and styles
        $content = preg_replace('/<script\b[^>]*>.*?<\/script>/is', '', $content);
        $content = preg_replace('/<style\b[^>]*>.*?<\/style>/is', '', $content);
        $content = preg_replace('/<nav\b[^>]*>.*?<\/nav>/is', '', $content);
        $content = preg_replace('/<header\b[^>]*>.*?<\/header>/is', '', $content);
        $content = preg_replace('/<footer\b[^>]*>.*?<\/footer>/is', '', $content);
        $content = preg_replace('/\s+/', ' ', $content);

        return [
            'title'   => trim(html_entity_decode(strip_tags($title))),
            'content' => trim($content),
        ];
    }

    protected function firstMatch(string $pattern, string $haystack): ?string
    {
        if (preg_match($pattern, $haystack, $m)) {
            return $m[1];
        }
        return null;
    }
}
