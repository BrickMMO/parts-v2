<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?=PAGE_TITLE ? PAGE_TITLE.' | ' : ''?>Parts Directory</title>

    <link rel="stylesheet" href="https://www.w3schools.com/w3css/5/w3.css">
    <link rel="stylesheet" href="https://cdn.brickmmo.com/exceptions@1.0.0/w3.css">

    <link rel="stylesheet" href="https://cdn.brickmmo.com/exceptions@1.0.0/fontawesome.css">

    <link rel="icon" type="image/x-icon" href="<?=SITE_URL?>/favicon.ico">

</head>
<body>

    <div class="w3-container" style="max-width: 1400px; margin: auto;">

        <nav class="w3-row w3-section">
            <div class="w3-col s6 w3-left-align">
                <a href="<?=SITE_URL?>/" style="font-size: 180%;">
                    Parts Directory
                </a>
            </div>
            <div class="w3-col s6 w3-right-align">
                
                <input class="w3-input w3-border" type="text" value="" placeholder="" style="max-width: 300px; display: inline-block; box-sizing: border-box; vertical-align: middle;" id="search-term">

                <a href="#" class="w3-button w3-white w3-border" style="display: inline-block; box-sizing: border-box; vertical-align: middle;" id="search-button">
                    <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i> Search
                </a>
        
            </div>
        </nav>

        <hr>
    