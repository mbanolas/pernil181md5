<div class="container">
    <!-- Content here -->
    <div class="jumbotron">
        <h2 class="display-4">Bienvenido a Gestión Pernil 181</h2>
        <h5>V 4.0 - CI <?php echo CI_VERSION; ?> - php <?php echo phpversion() ?> - MDB PRO 4.19.0 </h5>

        <hr class="my-4">
        <h3 class="lead_"><?php echo $this->session->nombre ?></h3>
        <h3 class="lead_"><?php echo $this->session->cargo ?></h3>

        <hr class="my-4">
        <h3>Apartados habilitados en actual programa: Productos</h3>
        <h3>Pulsando en <i class="fas fa-bars"></i> se muestra el menú y reconduce al anterior programa. </h3>
    </div>
</div>
<script type="text/javascript" src="https://unpkg.com/default-passive-events"></script>
