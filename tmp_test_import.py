import subprocess, os, textwrap
root = r'C:\xampp\htdocs\proker_balaidesa'
path = os.path.join(root, 'tmp_import_test.csv')
with open(path, 'w', encoding='utf-8', newline='') as f:
    f.write('rt,rw,no_kk,kepala_kk,nik,nama,jenis_kelamin,status_keluarga,tempat_lahir,tgl_lahir,status_pernikahan,agama,kewarganegaraan,suku,pendidikan,pekerjaan\n1,1,123,Test Kepala,1234567890123456,Test User,LAKI-LAKI,Kepala Keluarga,Test City,1990-01-02,Kawin,Islam,WNI,Jawa,SD,Petani\n')
php_code = textwrap.dedent('''
<?php
$_POST['import'] = true;
$_FILES['file_excel'] = [
  'name' => 'tmp_import_test.csv',
  'type' => 'text/csv',
  'tmp_name' => 'C:\\xampp\\htdocs\\proker_balaidesa\\tmp_import_test.csv',
  'error' => 0,
  'size' => 1,
];
include 'C:\\xampp\\htdocs\\proker_balaidesa\\pages\\proses_import.php';
''')
php_path = os.path.join(root, 'tmp_import_runner.php')
with open(php_path, 'w', encoding='utf-8') as f:
    f.write(php_code)
res = subprocess.run(['php', php_path], cwd=root, capture_output=True, text=True)
print('returncode=', res.returncode)
print('STDOUT:\n' + res.stdout[:4000])
print('STDERR:\n' + res.stderr[:4000])
