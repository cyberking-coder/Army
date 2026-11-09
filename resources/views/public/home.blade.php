<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ \App\Models\Setting::get('site_title', 'Territorial Army') }}</title>
    <meta name="description" content="{{ \App\Models\Setting::get('site_tagline') }}">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-900">
    <header class="bg-purple-800 text-white px-6 py-4">
        <h1 class="text-xl font-bold">{{ \App\Models\Setting::get('site_title') }}</h1>
    </header>
    <main class="max-w-5xl mx-auto p-8">
        <p>Phase 2 scaffolding complete. Public frontend built in Phase 4.</p>
        <p class="mt-4"><a href="/admin" class="text-purple-700 underline">Go to admin panel →</a></p>
    </main>
</body>
</html>
