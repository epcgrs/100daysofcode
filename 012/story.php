<?php
session_start();

$story = json_decode(file_get_contents('data/story.json'), true);

$scene = $_SESSION['current_scene'] ?? 'start';

if (isset($_POST['choice'])) {
    $choice = $_POST['choice'];
    if (isset($story[$scene]['choices'][$choice])) {
        $_SESSION['current_scene'] = $story[$scene]['choices'][$choice]['next_scene'];
        header('Location: story.php');
        exit();
    }
}

$current = $story[$scene];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aventura Medieval</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gradient-to-r from-green-900 to-gray-900 text-white min-h-screen flex flex-col justify-center items-center p-4">

    <div class="max-w-2xl bg-gray-800 p-8 rounded-2xl shadow-2xl">
        <h1 class="text-3xl font-bold mb-6 text-center">Aventura Medieval</h1>

        <p class="mb-6 text-lg"> <?php echo nl2br($current['text']); ?> </p>

        <?php if (!empty($current['choices'])): ?>
            <form method="post" class="space-y-4">
                <?php foreach ($current['choices'] as $key => $choice): ?>
                    <button name="choice" value="<?php echo $key; ?>" class="w-full bg-green-700 hover:bg-green-800 text-white py-3 px-6 rounded-xl transition"> 
                        <?php echo $choice['text']; ?>
                    </button>
                <?php endforeach; ?>
            </form>
        <?php else: ?>
            <a href="restart.php" class="mt-6 inline-block bg-red-700 hover:bg-red-800 py-3 px-6 rounded-xl">Recomeçar aventura</a>
        <?php endif; ?>
    </div>

</body>
</html>