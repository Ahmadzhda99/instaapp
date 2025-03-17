<!DOCTYPE html>
<html lang="id">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Login - InstaApp</title>
	<link href="//cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
	<style>
		body { background-color: #f8f9fa; }
		.card { max-width: 400px; margin: 50px auto; }
	</style>
</head>
<body>

<div class="container">
	<div class="card shadow-sm">
		<div class="card-body">
			<h3 class="text-center mb-4">Login</h3>
			<form method="post">
				<div class="mb-3">
					<label class="form-label">Username</label>
					<input type="text" class="form-control" name="username" required>
				</div>
				<div class="mb-3">
					<label class="form-label">Password</label>
					<input type="password" class="form-control" name="password" required>
				</div>
				<button type="submit" class="btn btn-primary w-100">Login</button>
			</form>
			<p class="text-center mt-3">Belum punya akun? <a href="<?= site_url('auth/register') ?>">Daftar</a></p>
		</div>
	</div>
</div>

<script src="//cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
