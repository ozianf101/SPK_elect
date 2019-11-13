<?php require_once('includes/init.php'); ?>
<?php cek_login($role = array(1, 2)); ?>

<?php
$errors = array();
$sukses = false;

$kode_alternatif = (isset($_POST['kode_alternatif'])) ? trim($_POST['kode_alternatif']) : '';
$nama_alternatif = (isset($_POST['nama_alternatif'])) ? trim($_POST['nama_alternatif']) : '';
$kriteria = (isset($_POST['kriteria'])) ? $_POST['kriteria'] : array();


if(isset($_POST['submit'])):	
	
	// Validasi
	if(!$kode_alternatif) {
		$errors[] = 'Kode Alternatif tidak boleh kosong';
	}	
	
	
	// Jika lolos validasi lakukan hal di bawah ini
	if(empty($errors)):
		
		$handle = $pdo->prepare('INSERT INTO alternatif (kode_alternatif, nama_alternatif, tanggal_input) VALUES (:kode_alternatif, :nama_alternatif, :tanggal_input)');
		$handle->execute( array(
			'kode_alternatif' => $kode_alternatif,
			'nama_alternatif' => $nama_alternatif,
			'tanggal_input' => date('Y-m-d')
		) );
		$sukses = "Calon dengan Kode. <strong>{$kode_alternatif}</strong> berhasil dimasukkan.";
		$id_alternatif = $pdo->lastInsertId();
		
		// Jika ada kriteria yang diinputkan:
		if(!empty($kriteria)):
			foreach($kriteria as $id_kriteria => $nilai):
				$handle = $pdo->prepare('INSERT INTO nilai_alternatif (id_alternatif, id_kriteria, nilai) VALUES (:id_alternatif, :id_kriteria, :nilai)');
				$handle->execute( array(
					'id_alternatif' => $id_alternatif,
					'id_kriteria' => $id_kriteria,
					'nilai' =>$nilai
				) );
			endforeach;
		endif;
		
		redirect_to('list-alternatif.php?status=sukses-baru');		
		
	endif;

endif;
?>

<?php
$judul_page = 'Tambah Alternatif';
require_once('template-parts/header.php');
?>

	<div class="main-content-row">
	<div class="container clearfix">
	
		<?php include_once('template-parts/sidebar-alternatif.php'); ?>
	
		<div class="main-content the-content">
			<h1>Tambah Alternatif</h1>
			
			<?php if(!empty($errors)): ?>
			
				<div class="msg-box warning-box">
					<p><strong>Error:</strong></p>
					<ul>
						<?php foreach($errors as $error): ?>
							<li><?php echo $error; ?></li>
						<?php endforeach; ?>
					</ul>
				</div>
				
			<?php endif; ?>			
			
			
				<form action="tambah-alternatif.php" method="post">
					<div class="field-wrap clearfix">					
						<label>Kode Alternatif <span class="red">*</span></label>
						<input type="text" name="kode_alternatif" value="<?php echo $kode_alternatif; ?>">
					</div>					
					<div class="field-wrap clearfix">					
						<label>Nama Alternatif</label>
						<input type="text" name="nama_alternatif" cols="30" value="<?php echo $nama_alternatif; ?>"</textarea>
					</div>			
					
					<h3>Nilai Kriteria</h3>
					<?php
					$query = $pdo->prepare('SELECT id_kriteria, kode, ada_pilihan FROM kriteria ORDER BY urutan_order ASC');			
					$query->execute();
					// menampilkan berupa nama field
					$query->setFetchMode(PDO::FETCH_ASSOC);
					
					if($query->rowCount() > 0):
					
						while($kriteria = $query->fetch()):							
						?>
						
							<div class="field-wrap clearfix">					
								<label><?php echo $kriteria['kode']; ?></label>
								<?php if(!$kriteria['ada_pilihan']): ?>
									<input type="number" step="1" name="kriteria[<?php echo $kriteria['id_kriteria']; ?>]">								
								<?php else: ?>
									
									<select name="kriteria[<?php echo $kriteria['id_kriteria']; ?>]">
										<option value="0">-- Pilih Variabel --</option>
										<?php
										$query3 = $pdo->prepare('SELECT * FROM pilihan_kriteria WHERE id_kriteria = :id_kriteria ORDER BY urutan_order ASC');			
										$query3->execute(array(
											'id_kriteria' => $kriteria['id_kriteria']
										));
										// menampilkan berupa nama field
										$query3->setFetchMode(PDO::FETCH_ASSOC);
										if($query3->rowCount() > 0): while($hasl = $query3->fetch()):
										?>
											<option value="<?php echo $hasl['nilai']; ?>"><?php echo $hasl['nama']; ?></option>
										<?php
										endwhile; endif;
										?>
									</select>
									
								<?php endif; ?>
							</div>	
						
						<?php
						endwhile;
						
					else:					
						echo '<p>Kriteria masih kosong.</p>';						
					endif;
					?>
					
					<div class="field-wrap clearfix">
						<button type="submit" name="submit" value="submit" class="button">Tambah Alternatif</button>
					</div>
				</form>
					
			
		</div>
	
	</div><!-- .container -->
	</div><!-- .main-content-row -->


<?php
require_once('template-parts/footer.php');