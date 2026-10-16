import sqlite3
import subprocess
import os

php_exe = os.path.join(os.path.expanduser('~'), 'php', 'php.exe')
hash_output = subprocess.check_output([php_exe, '-r', "echo password_hash('teste123', PASSWORD_DEFAULT);"]).decode('utf-8').strip()

print('Gerado PHP hash para teste123:', hash_output)

conn = sqlite3.connect('database/database.sqlite')
c = conn.cursor()

c.execute("UPDATE admins SET nome = 'teste123', email = 'caeca@unifei.edu.br', senha_hash = ?", (hash_output,))
c.execute("UPDATE alunos SET nome = 'teste123', email = 'teste123@unifei.edu.br', senha_hash = ? WHERE matricula = '2022001234'", (hash_output,))
c.execute("UPDATE alunos SET senha_hash = ? WHERE matricula = '2024009999'", (hash_output,))

conn.commit()

# Testar se a consulta por nome ou email encontra
c.execute("SELECT id, nome, email, matricula, status FROM alunos WHERE LOWER(nome) = 'teste123'")
aluno = c.fetchone()
print('Aluno localizado por nome teste123:', aluno)

c.execute("SELECT id, nome, email FROM admins WHERE LOWER(nome) = 'teste123'")
admin = c.fetchone()
print('Admin localizado por nome teste123:', admin)

conn.close()
print("Todas as senhas foram redefinidas para teste123!")
