
        <hr>

        <footer class="w3-section w3-text-center">
            
            <a href="https://brickmmo.com">BrickMMO</a> | &copy;2024
            <br>
            <span class="w3-text-grey">
                LEGO, the LEGO logo and the Minifigure are trademarks of the LEGO Group.
            </span>
        </footer>

    </div>

    <script 
        src="<?=ENV_LOCAL ? 'http://sso.local.brickmmo.com:33/bar.js' : 'https://cdn.brickmmo.com/bar@1.1.0/bar.js'?>"
        data-console="false"
        data-menu="false"
        data-admin="false"
        data-local="<?=ENV_LOCAL ? 'true' : 'false'?>"
        data-https="<?=ENV_LOCAL ? 'false' : 'true'?>"
    ></script>

    <script src="https://kit.fontawesome.com/a74f41de6e.js" crossorigin="anonymous"></script>

</body>
</html>
