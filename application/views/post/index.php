<!DOCTYPE html>
<html lang="id">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>InstaApp</title>
	<link href="//cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
	<style>
		.post-card { margin-bottom: 20px; }
		.like-btn { cursor: pointer; }
	</style>
</head>
<body class="bg-light">
	<!-- Navbar -->
	<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
		<div class="container">
			<a class="navbar-brand" href="#">InstaApp</a>
			<div class="d-flex">
				<?php if ($this->session->userdata('user_id')): ?>
					<span class="navbar-text text-white me-3">
						Halo, <b><?= $this->session->userdata('username') ?></b>!
					</span>
					<a href="javascript:void(0);" class="btn btn-success me-2" data-bs-toggle="modal" data-bs-target="#uploadModal">Upload Post</a>
					<a href="<?= site_url('auth/logout') ?>" class="btn btn-danger">Logout</a>
				<?php else: ?>
					<a href="<?= site_url('auth/login') ?>" class="btn btn-outline-light me-2">Login</a>
					<a href="<?= site_url('auth/register') ?>" class="btn btn-warning">Register</a>
				<?php endif; ?>
			</div>
		</div>
	</nav>

	<!-- Konten Utama -->
	<div class="container mt-4">
		<h2 class="text-center mb-4">InstaApp</h2>
		<?php if($posts): ?>
			<?php foreach ($posts as $post): ?>
				<div class="card post-card">
					<div class="card-body">
						<h5 class="card-title"><?= $post['username'] ?></h5>
						<p class="card-text"><?= $post['caption'] ?></p>
						<img src="<?= base_url('uploads/'.$post['image']) ?>" class="img-fluid rounded" width="300">

						<!-- Like Section -->
						<div class="mt-2">
							<?php if ($this->session->userdata('user_id')): ?>
								<span class="like-btn" data-post="<?= $post['id'] ?>">
									<button class="btn btn-outline-primary btn-sm">
										<span class="like-text">
											<?= $this->m_like->user_liked($this->session->userdata('user_id'), $post['id']) ? 'Unlike' : 'Like' ?>
										</span>
									</button>
								</span>
							<?php endif; ?>
							<span class="ms-2"><b class="like-count<?= $post['id'] ?>"><?= $this->m_like->get_likes_count($post['id']) ?></b> Likes</span>
						</div>

						<!-- Komentar Section -->
						<div class="mt-3">
							<h6>Komentar:</h6>
							<ul class="list-group comment-list">
								<?php foreach ($this->m_comment->get_comments($post['id']) as $comment): ?>
									<li class="list-group-item"><?= $comment['username'] ?>: <?= $comment['comment'] ?></li>
								<?php endforeach; ?>
							</ul>

							<?php if ($this->session->userdata('user_id')): ?>
								<!-- Form Komentar -->
								<form class="comment-form mt-2" data-post="<?= $post['id'] ?>">
									<div class="input-group">
										<input type="text" class="form-control comment-input" placeholder="Tambah komentar">
										<button type="submit" class="btn btn-primary">Kirim</button>
									</div>
								</form>
							<?php else: ?>
								<p class="text-muted">Silakan <a href="<?= site_url('auth/login') ?>">Login</a> untuk berkomentar.</p>
							<?php endif; ?>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		<?php else: ?>
			<div class="card post-card empty-card">
				<div class="card-body">
					<h5 class="card-title text-center">Belum Ada Post</h5>
				</div>
			</div>
		<?php endif; ?>
	</div>

	<!-- Modal Upload -->
	<div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="uploadModalLabel">Upload Post Baru</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<form id="uploadForm" enctype="multipart/form-data">
						<div class="mb-3">
							<label for="caption" class="form-label">Caption</label>
							<input type="text" class="form-control" id="caption" name="caption" required>
						</div>
						<div class="mb-3">
							<label for="image" class="form-label">Gambar</label>
							<input type="file" class="form-control" id="image" name="image" accept="image/*" required>
							<img id="previewImage" src="#" class="img-fluid mt-2 d-none" width="200">
						</div>
						<button type="submit" class="btn btn-primary w-100">Upload</button>
					</form>
				</div>
			</div>
		</div>
	</div>

<!-- Bootstrap JS -->
<script src="//cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
	$(document).ready(function () {
		// Like Button Click
		$(".like-btn").click(function () {
			var postID = $(this).data("post");
			var btn = $(this).find(".like-text");

			$.post("<?= site_url('post/like/') ?>" + postID, function (response) {
				var data = JSON.parse(response);

				if (data.status === "success") {
					btn.text(data.liked ? "Unlike" : "Like");
					$(`.like-count${postID}`).text(data.like_count);
					alert("Berhasil " + (data.liked ? "menyukai" : "membatalkan like") + " postingan!");
				} else {
					alert(data.message);
				}
			});
		});


		// Preview Gambar Sebelum Upload
		$("#image").change(function (e) {
			var file = e.target.files[0];
			if (file) {
				var reader = new FileReader();
				reader.onload = function (e) {
					$("#previewImage").attr("src", e.target.result).removeClass("d-none");
				};
				reader.readAsDataURL(file);
			}
		});

		// Upload Form Submit via AJAX
		$("#uploadForm").submit(function (e) {
			e.preventDefault();
			var formData = new FormData(this);

			$.ajax({
				url: "<?= site_url('post/upload') ?>",
				type: "POST",
				data: formData,
				processData: false,
				contentType: false,
				beforeSend: function () {
					$("#uploadForm button").text("Uploading...").prop("disabled", true);
				},
				success: function (response) {
					$("#uploadForm button").text("Upload").prop("disabled", false);
					$("#uploadModal").modal("hide");
					$("#uploadForm")[0].reset();
					$("#previewImage").addClass("d-none");
					$('.empty-card').remove()

					// Tambahkan post baru tanpa reload halaman
					var newPost = `
						<div class="card post-card">
							<div class="card-body">
								<h5 class="card-title"><?= $this->session->userdata('username') ?></h5>
						<p class="card-text">` + response.caption + `</p>
						<img src="<?= base_url('uploads/') ?>` + response.image + `" class="img-fluid rounded" width="300">
								<div class="mt-2">
						<span class="like-btn" data-post="` + response.id + `">
										<button class="btn btn-outline-primary btn-sm">
											<span class="like-text">Like</span>
										</button>
									</span>
									<span class="ms-2"><b class="like-count">0</b> Likes</span>
								</div>

						<div class="mt-3">
							<h6>Komentar:</h6>
							<ul class="list-group comment-list">
									<li class="list-group-item"></li>
							</ul>
								<form class="comment-form mt-2" data-post="` + response.id + `">
									<div class="input-group">
										<input type="text" class="form-control comment-input" placeholder="Tambah komentar">
										<button type="submit" class="btn btn-primary">Kirim</button>
									</div>
								</form>
						</div>
							</div>
						</div>
					`;
					$(".container.mt-4").append(newPost);
				},
				error: function () {
					$("#uploadForm button").text("Upload").prop("disabled", false);
					alert("Upload gagal! Coba lagi.");
				}
			});
		});

		$(".comment-form").submit(function (e) {
			e.preventDefault();
			var postID = $(this).data("post");
			var commentInput = $(this).find(".comment-input");
			var commentList = $(this).siblings(".comment-list");

			$.post("<?= site_url('post/comment/') ?>" + postID, {comment: commentInput.val()}, function (data) {
				if (commentInput.val().trim() !== "") {
					commentList.append('<li class="list-group-item">' + data.username + ': ' + commentInput.val() + '</li>');
					commentInput.val("");
				}
			});
		});
	});
</script>

</body>
</html>
