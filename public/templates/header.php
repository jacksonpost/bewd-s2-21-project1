<!doctype html>
<html lang="en">

<head>

    <title><?php echo $_SESSION["username"];?>'s Collection</title>
    <meta charset="utf-8">

    <link rel="stylesheet" href="assets/css/spectre.css">
    <!-- add our own stylesheet to extend/override Spectre -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <header>
        <div class="container">
            <h1><a href="index.php">Collection tracker</a></h1>
            <nav>
                <ul>
                    <li><a href="create.php">Add a new artwork</a></li>
                    <li><a href="read.php">Find an artwork</a></li>
                    <li><a href="update.php">Update an artwork</a></li>
                    <li><a href="delete.php">Delete an artwork</a></li>
                </ul>
            </nav>
            <form method="post" name="search" class="search" onsubmit="search_records(event)">
                <label for="search-box">Search the records</label>
                <input type="search" id="search-box" name="q" autocomplete="off" placeholder="search">
                <input type="submit" value="search" >
            </form>
            <div id="search-results"></div>

            <script>
                // --------- ajax for search
                function search_records(e) {
                    // prevent form from refreshing page
                    e.preventDefault();

                    searchBox = document.getElementById('search-box');
                    str = searchBox.value;
                    
                    if (str == "") {
                        return;
                    } else {
                        var xmlhttp = new XMLHttpRequest();
                        xmlhttp.onreadystatechange = function() {
                            if(xmlhttp.readyState === XMLHttpRequest.DONE) {
                                var status = xmlhttp.status;
                                if (status === 0 || (status >= 200 && status < 400)) {
                                    searchResults = document.getElementById('search-results');
                                    searchResults.innerHTML = this.responseText;
                                    closeBtn = document.createElement("button");
                                    closeBtn.innerHTML = 'close';
                                    closeBtn.addEventListener('click', function() { /* clear search results */ }, false);
                                    searchResults.innerHTML += closeBtn;
                                }
                            }
                        };
                        xmlhttp.open("GET","search.php?q="+str,true);
                        xmlhttp.send();
                    }
                }
                // -----------
            </script>
        </div>
    </header>
    <main>
        <div class="container">
    