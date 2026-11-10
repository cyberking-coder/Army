<?php

namespace App\Console\Commands;

use App\Models\Book;
use App\Models\OfficerSubscriber;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Page;
use App\Models\Pdf;
use App\Models\Redirect;
use App\Models\ResultRegistration;
use App\Models\SoldierSubscriber;
use App\Models\Exam;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Import legacy data from CSV files.
 *
 * Usage:
 *   php artisan legacy:import pages           storage/migration/pages.csv
 *   php artisan legacy:import books           storage/migration/books.csv
 *   php artisan legacy:import pdfs            storage/migration/pdfs.csv
 *   php artisan legacy:import exams           storage/migration/exams.csv
 *   php artisan legacy:import officers        storage/migration/officers.csv
 *   php artisan legacy:import soldiers        storage/migration/soldiers.csv
 *   php artisan legacy:import orders          storage/migration/orders.csv
 *   php artisan legacy:import registrations   storage/migration/registrations.csv
 *   php artisan legacy:import redirects       storage/migration/redirects.csv
 *
 * CSV column headers must match the template files in storage/migration/.
 * Idempotent: re-running updates existing rows (matched by key column).
 */
class ImportLegacy extends Command
{
    protected $signature = 'legacy:import {type} {file} {--dry-run : Preview without writing}';
    protected $description = 'Import legacy data from a CSV file';

    public function handle(): int
    {
        $type = $this->argument('type');
        $file = base_path($this->argument('file'));
        $dry  = (bool) $this->option('dry-run');

        if (! file_exists($file)) {
            $this->error("File not found: $file");
            return self::FAILURE;
        }

        $rows = $this->readCsv($file);
        $this->info("Read ".count($rows)." rows from ".basename($file));

        if (empty($rows)) {
            return self::SUCCESS;
        }

        try {
            DB::beginTransaction();
            $imported = match ($type) {
                'pages'         => $this->importPages($rows),
                'books'         => $this->importBooks($rows),
                'pdfs'          => $this->importPdfs($rows),
                'exams'         => $this->importExams($rows),
                'officers'      => $this->importOfficers($rows),
                'soldiers'      => $this->importSoldiers($rows),
                'orders'        => $this->importOrders($rows),
                'registrations' => $this->importRegistrations($rows),
                'redirects'     => $this->importRedirects($rows),
                default         => throw new \InvalidArgumentException("Unknown type: $type"),
            };

            if ($dry) {
                DB::rollBack();
                $this->warn("--dry-run: $imported rows would be imported, nothing saved.");
            } else {
                DB::commit();
                $this->info("✓ $imported rows imported.");
            }
        } catch (\Throwable $e) {
            DB::rollBack();
            $this->error('Import failed: '.$e->getMessage());
            return self::FAILURE;
        }

        return self::SUCCESS;
    }

    protected function readCsv(string $file): array
    {
        $handle = fopen($file, 'r');
        $headers = array_map('trim', fgetcsv($handle));
        $rows = [];

        while (($cols = fgetcsv($handle)) !== false) {
            if (count($cols) !== count($headers)) continue;
            $row = array_combine($headers, array_map(fn ($v) => $v === '' ? null : $v, $cols));
            if (array_filter($row)) $rows[] = $row;
        }
        fclose($handle);
        return $rows;
    }

    protected function importPages(array $rows): int
    {
        // Two-pass: parents first, then children (resolve parent_slug -> parent_id)
        $parentMap = [];
        $count = 0;

        foreach ($rows as $row) {
            if (empty($row['parent_slug'])) {
                $page = Page::updateOrCreate(
                    ['slug' => $row['slug'] ?: Str::slug($row['title'])],
                    [
                        'title'       => $row['title'],
                        'menu_name'   => $row['menu_name'] ?: $row['title'],
                        'description' => $row['description'] ?? '',
                        'status'      => $row['status'] ?? 'publish',
                        'sort'        => (int) ($row['sort'] ?? 0),
                    ]
                );
                $parentMap[$page->slug] = $page->id;
                $count++;
            }
        }

        foreach ($rows as $row) {
            if (! empty($row['parent_slug'])) {
                $parentId = $parentMap[$row['parent_slug']] ?? Page::where('slug', $row['parent_slug'])->value('id');
                if (! $parentId) {
                    $this->warn("Skipping {$row['slug']}: parent {$row['parent_slug']} not found");
                    continue;
                }

                Page::updateOrCreate(
                    ['slug' => $row['slug'] ?: Str::slug($row['title'])],
                    [
                        'title'       => $row['title'],
                        'parent_id'   => $parentId,
                        'menu_name'   => $row['menu_name'] ?: $row['title'],
                        'description' => $row['description'] ?? '',
                        'status'      => $row['status'] ?? 'publish',
                        'sort'        => (int) ($row['sort'] ?? 0),
                    ]
                );
                $count++;
            }
        }
        return $count;
    }

    protected function importBooks(array $rows): int
    {
        foreach ($rows as $row) {
            Book::updateOrCreate(
                ['slug' => $row['slug'] ?: Str::slug($row['title'])],
                [
                    'title'          => $row['title'],
                    'author'         => $row['author'] ?? null,
                    'description'    => $row['description'] ?? null,
                    'price'          => (float) $row['price'],
                    'discount_price' => $row['discount_price'] ?? null,
                    'stock'          => (int) ($row['stock'] ?? 0),
                    'is_active'      => (bool) ($row['is_active'] ?? true),
                ]
            );
        }
        return count($rows);
    }

    protected function importPdfs(array $rows): int
    {
        foreach ($rows as $row) {
            Pdf::updateOrCreate(
                ['slug' => $row['slug'] ?: Str::slug($row['title'])],
                [
                    'title'           => $row['title'],
                    'description'     => $row['description'] ?? null,
                    'file_path'       => $row['file_path'],
                    'category'        => $row['category'] ?? null,
                    'file_size_kb'    => (int) ($row['file_size_kb'] ?? 0),
                    'downloads_count' => (int) ($row['downloads_count'] ?? 0),
                    'is_active'       => (bool) ($row['is_active'] ?? true),
                ]
            );
        }
        return count($rows);
    }

    protected function importExams(array $rows): int
    {
        foreach ($rows as $row) {
            Exam::updateOrCreate(
                ['slug' => $row['slug'] ?: Str::slug($row['name'])],
                [
                    'name'        => $row['name'],
                    'description' => $row['description'] ?? null,
                    'exam_date'   => $row['exam_date'] ?? null,
                    'is_active'   => (bool) ($row['is_active'] ?? true),
                ]
            );
        }
        return count($rows);
    }

    protected function importOfficers(array $rows): int
    {
        foreach ($rows as $row) {
            OfficerSubscriber::updateOrCreate(
                ['email' => $row['email']],
                [
                    'full_name'   => $row['full_name'],
                    'mobile'      => $row['mobile'],
                    'status'      => $row['status'] ?? 'subscribed',
                    'verified_at' => $row['verified_at'] ?? now(),
                ]
            );
        }
        return count($rows);
    }

    protected function importSoldiers(array $rows): int
    {
        foreach ($rows as $row) {
            SoldierSubscriber::updateOrCreate(
                ['email' => $row['email']],
                [
                    'first_name'  => $row['first_name'],
                    'last_name'   => $row['last_name'] ?? '',
                    'mobile'      => $row['mobile'],
                    'status'      => $row['status'] ?? 'subscribed',
                    'verified_at' => $row['verified_at'] ?? now(),
                ]
            );
        }
        return count($rows);
    }

    protected function importOrders(array $rows): int
    {
        foreach ($rows as $row) {
            Order::updateOrCreate(
                ['order_number' => $row['order_number']],
                [
                    'full_name'          => $row['full_name'],
                    'mobile'             => $row['mobile'],
                    'email'              => $row['email'],
                    'shipping_address'   => $row['shipping_address'],
                    'city'               => $row['city'],
                    'state'              => $row['state'],
                    'pincode'            => $row['pincode'],
                    'subtotal'           => (float) $row['subtotal'],
                    'discount'           => (float) ($row['discount'] ?? 0),
                    'final_cost'         => (float) $row['final_cost'],
                    'status'             => $row['status'] ?? 'successful',
                    'payment_id'         => $row['payment_id'] ?? null,
                    'consignment_number' => $row['consignment_number'] ?? null,
                    'notes'              => $row['notes'] ?? null,
                    'created_at'         => $row['created_at'] ?? now(),
                ]
            );
        }
        return count($rows);
    }

    protected function importRegistrations(array $rows): int
    {
        foreach ($rows as $row) {
            $examId = Exam::where('slug', $row['exam_slug'])->value('id');
            if (! $examId) {
                $this->warn("Skipping registration for {$row['name']}: exam {$row['exam_slug']} not found");
                continue;
            }
            ResultRegistration::create([
                'exam_id'      => $examId,
                'name'         => $row['name'],
                'mobile'       => $row['mobile'],
                'email'        => $row['email'],
                'roll_no'      => $row['roll_no'],
                'zone'         => $row['zone'],
                'submitted_at' => $row['submitted_at'] ?? now(),
            ]);
        }
        return count($rows);
    }

    protected function importRedirects(array $rows): int
    {
        foreach ($rows as $row) {
            Redirect::updateOrCreate(
                ['from_path' => $row['from_path']],
                [
                    'to_path'     => $row['to_path'],
                    'status_code' => (int) ($row['status_code'] ?? 301),
                ]
            );
        }
        return count($rows);
    }
}
