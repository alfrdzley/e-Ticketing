<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pines UI</title>
    <script src="//unpkg.com/alpinejs" defer></script>
    <style>
        [x-cloak] {
            display: none
        }
    </style>
</head>

<body>
    <div x-data="{ open: false }" class="p-4 border">
        <button @click="open = !open" class="px-2 py-1 bg-blue-500 text-white">Toggle</button>
        <span x-show="open" class="ml-2">Alpine aktif!</span>
    </div>
    <script>
        document.querySelector('[x-data]').__x.$data.open
    </script>
</body>

</html>