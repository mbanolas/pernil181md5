<div class="container">
	<div class="row">
		<div class="col">
		</div>
		<div class="col-6">
			<div class="card card-cascade narrower center mt-5 ">

				<div class="view view-cascade overlay">
					<img class="card-img-top" src="<?php echo $base_url ?>/images/pernil181.jpeg" alt="Card image cap">
					<a>
						<div class="mask rgba-white-slight"></div>
					</a>
				</div>

				<!-- Card content -->
				<div class="card-body px-lg-5">

					<form class="needs-validation" autocomplete="on" novalidate action="<?php echo $base_url ?>/index.php/validacion">
						<div class="form-row">
							<div class="col-md-4 mb-3 md-form">
								<label for="usuario" class="active">Usuario</label>
								<input type="text"  class="form-control " id="usuario" aria-describedby="inputGroupPrepend2"  required>
								<div class="invalid-feedback">
									Se requier un valor
								</div>
								<div class="valid-feedback">
									OK
								</div>
							</div>
						</div>
						<div class="form-row">
							<div class="col-md-6 mb-3 md-form">
								<label for="password" class="active">Contraseña</label>
								<input type="password"  autocomplete class="form-control " id="password" required>
								<div class="invalid-feedback">
									Se requier un valor
								</div>
								<div class="valid-feedback">
									OK
								</div>
							</div>
						</div>
						<div class="alert alert-danger d-none" role="alert">
						</div>
						<button class="btn btn-primary btn-lg btn-rounded" type="submit">Entrar</button>
					</form>

				</div>


			</div>
			<div class="mt-2"><?php echo copyright() 
								?></div>
		</div>
		<div class="col">
		</div>
	</div>
</div>

<script type="text/javascript" src="https://unpkg.com/default-passive-events"></script>

<script>
	$(document).ready(function() {
		// $('input#usuario').parent().children($('label')).addClass('active')
		// $('input#password').parent().children($('label')).addClass('active')
		$('input#usuario').prev($('label')).addClass('active')
		$('input#password').prev($('label')).addClass('active')

		$('button[type="submit"]').click(function(event) {
			var forms = document.getElementsByClassName('needs-validation');
			var validation = Array.prototype.filter.call(forms, function(form) {
				// form.addEventListener('submit', function(event) {
					if (form.checkValidity() === false) {
						event.preventDefault();
						event.stopPropagation();
						form.classList.add('was-validated');
						return false
					}
					form.classList.add('was-validated');
					event.preventDefault();
					event.stopPropagation();					
					$('.alert').addClass('d-none')
					$.ajax({
					type: "POST",
					url: "<?php echo base_url() ?>" + "index.php/pernil181/login",
					data: {
						username: $('input#usuario').val(),
						password: $('input#password').val()
					},
					success: function(datos) {
						console.log(datos)
						var datos = $.parseJSON(datos)
						if(!datos){
							$('.alert').removeClass('d-none').html("La contraseña NO corresponde al usuario");
						}else{
							location.href = "<?php echo base_url() ?>" + "index.php/bienvenida";
						}
					},
					error: function() {
						$('.alert').removeClass('d-none')
						$('.alert').html("Información importante. Error en el proceso validacion. Informar");
					}
				})
			});
		})
	})

</script> 