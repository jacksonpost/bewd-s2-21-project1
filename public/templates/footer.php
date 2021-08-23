        </div>
    </main>
    <footer>
        <div class="container">
            <p>Logged in as: <?php echo $_SESSION["username"]; ?></p>
            <ul class="nav">
                <li><a href="logout.php">Logout</a></li>
                <li><a href="pwd-reset.php">Reset Password</a></li>
            </ul>
        </div>
    </footer>
</body>
</html>