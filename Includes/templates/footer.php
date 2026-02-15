<?php
// You could define variables here
 $siteName = "CarRental";
 $currentYear = date('Y');
 $footerLinks = [
    ['url' => 'orders.php', 'text' => 'Orders'],
    ['url' => 'terms.php', 'text' => 'Terms'],
    ['url' => 'report.php', 'text' => 'Report Problem']
];
?>

<!-- Footer Bottom Section -->
<footer class="footer_section">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="copyright">
                    <?php echo htmlspecialchars($siteName); ?> © 
                    <?php echo $currentYear; ?>
                    All rights reserved 
                </div>
            </div>
            <div class="col-md-6">
                <ul class="footer_link">
                    <?php foreach ($footerLinks as $link): ?>
                        <li><a href="<?php echo htmlspecialchars($link['url']); ?>"><?php echo htmlspecialchars($link['text']); ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</footer>

<!-- INCLUDE JS SCRIPTS -->
<script src="https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js"></script>
<script src="Design/js/jquery.min.js"></script>
<script src="Design/js/bootstrap-js/bootstrap.min.js"></script>
<script src="Design/js/bootstrap-js/bootstrap.bundle.min.js"></script>
<script src="Design/js/main-script.js"></script>

</body>

<!-- END BODY TAG -->

</html>

<!-- END HTML TAG -->