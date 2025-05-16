<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minecraft Player Stats</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #1e1e1e;
            color: #fff;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .search-box {
            margin-top: 40px;
        }
        .player-card {
            background-color: #2c2f33;
            border-radius: 16px;
            padding: 30px;
            box-shadow: 0 0 15px rgba(0,0,0,0.5);
        }
        .stat-icon {
            width: 64px;
            height: 64px;
        }
        .stat-title {
            font-size: 1.1rem;
            color: #ffc107;
            font-weight: 500;
        }
        .stat-subtitle {
            font-size: 1rem;
            color: #ccc;
        }
        .stat-value {
            font-size: 1.8rem;
            font-weight: bold;
            color: #7CFC00;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="row justify-content-center search-box">
        <div class="col-md-6">
            <form method="GET" action="" class="input-group">
                <input type="text" name="username" class="form-control" placeholder="Search Minecraft Username..." required>
                <button class="btn btn-warning" type="submit">Search</button>
            </form>
        </div>
    </div>

    <?php
    require_once 'db.php';

    function getUUIDFromUsername($username) {
        $response = @file_get_contents("https://api.mojang.com/users/profiles/minecraft/$username");
        if ($response === FALSE) return null;

        $data = json_decode($response, true);
        return $data['id'] ?? null;
    }

    if (isset($_GET['username'])) {
        $username = htmlspecialchars($_GET['username']);
        $uuid = getUUIDFromUsername($username);

        if ($uuid) {
            // Format UUID to match the DB format
            $formattedUUID = substr($uuid, 0, 8) . '-' . substr($uuid, 8, 4) . '-' . substr($uuid, 12, 4) . '-' . substr($uuid, 16, 4) . '-' . substr($uuid, 20);
            $stats = getPlayerStatsByUUID($formattedUUID);

            if ($stats):
    ?>
                <div class="row justify-content-center mt-5">
                    <div class="col-md-8 player-card text-center">
                        <img src="https://mc-heads.net/body/<?= $uuid ?>" class="img-fluid rounded mb-3" alt="Player Skin">
                        <h2 class="mb-4 text-warning">Stats for <?= $stats['username'] ?></h2>

                        <div class="container mt-4">
                            <div class="row text-center">
                                <div class="col-4">
                                    <img src="assets/icons/pickaxe.png" class="stat-icon" alt="Blocks Broken">
                                    <div class="stat-title">Blocks</div>
                                    <div class="stat-subtitle">Broken</div>
                                    <div class="stat-value"><?= $stats['total_blocks_broken'] ?></div>
                                </div>
                                <div class="col-4">
                                    <img src="assets/icons/sword.png" class="stat-icon" alt="Mobs Killed">
                                    <div class="stat-title">Mobs</div>
                                    <div class="stat-subtitle">Killed</div>
                                    <div class="stat-value"><?= $stats['total_mobs_killed'] ?></div>
                                </div>
                                <div class="col-4">
                                    <img src="assets/icons/wheat.png" class="stat-icon" alt="Animals Bred">
                                    <div class="stat-title">Animals</div>
                                    <div class="stat-subtitle">Bred</div>
                                    <div class="stat-value"><?= $stats['total_animals_bred'] ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
    <?php
            else:
    ?>
                <div class="row justify-content-center mt-5">
                    <div class="col-md-6 text-center alert alert-danger">
                        No stats found for this player.
                    </div>
                </div>
    <?php
            endif;
        } else {
    ?>
            <div class="row justify-content-center mt-5">
                <div class="col-md-6 text-center alert alert-danger">
                    Invalid Minecraft username.
                </div>
            </div>
    <?php
        }
    }
    ?>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
