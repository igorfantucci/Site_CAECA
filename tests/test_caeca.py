"""
Testes de Integridade e Validação do Sistema CAECA UNIFEI
"""

import sqlite3
import os
import sys

def test_database():
    db_path = os.path.join(os.path.dirname(__file__), '..', 'database', 'database.sqlite')
    assert os.path.exists(db_path), f"Arquivo de banco {db_path} não encontrado!"
    
    conn = sqlite3.connect(db_path)
    cursor = conn.cursor()

    # 1. Verifica tabelas obrigatórias
    tables = [
        'admins', 'alunos', 'professores', 'disciplinas',
        'prerequisitos', 'provas', 'laboratorios', 'monitores', 'empresas', 'equivalencias'
    ]
    cursor.execute("SELECT name FROM sqlite_master WHERE type='table'")
    existing_tables = [r[0] for r in cursor.fetchall()]
    for t in tables:
        assert t in existing_tables, f"Tabela obrigatória ausente: {t}"
    print("[PASS] Todas as tabelas do schema (incluindo equivalencias) existem no banco.")

    # 2. Verifica disciplinas (Obrigatórias + Optativas do PPC)
    cursor.execute("SELECT COUNT(*), SUM(CASE WHEN tipo='optativa' THEN 1 ELSE 0 END) FROM disciplinas")
    total_disc, total_optativas = cursor.fetchone()
    assert total_disc >= 70, f"Esperado >= 70 disciplinas, encontrado {total_disc}"
    assert total_optativas >= 20, f"Esperado >= 20 optativas, encontrado {total_optativas}"
    print(f"[PASS] Disciplinas cadastradas: {total_disc} (Obrigatórias: {total_disc - total_optativas} / Optativas: {total_optativas}).")

    # 3. Verifica tabela de equivalências
    cursor.execute("SELECT COUNT(*) FROM equivalencias")
    total_equiv = cursor.fetchone()[0]
    assert total_equiv >= 25, f"Esperado >= 25 equivalências, encontrado {total_equiv}"
    print(f"[PASS] Tabela de equivalências curriculares: {total_equiv} mapeamentos ativos.")

    # 4. Verifica pré-requisitos
    cursor.execute("SELECT COUNT(*) FROM prerequisitos")
    total_prereqs = cursor.fetchone()[0]
    assert total_prereqs > 20, f"Esperado > 20 relacionamentos de pré-requisitos, encontrado {total_prereqs}"
    print(f"[PASS] Relações de pré-requisito cadastradas: {total_prereqs}.")

    # 5. Verifica acervo de provas físicas
    cursor.execute("SELECT id, arquivo_path FROM provas")
    provas = cursor.fetchall()
    assert len(provas) > 0, "Nenhuma prova cadastrada para teste de acervo!"
    for pid, arq in provas:
        full_pdf_path = os.path.join(os.path.dirname(__file__), '..', 'protected', 'provas', arq)
        assert os.path.exists(full_pdf_path), f"Arquivo PDF não encontrado: {full_pdf_path}"
    print(f"[PASS] Acervo de provas físicas verificado ({len(provas)} PDFs em protected/provas/).")

    conn.close()
    print("\nTODOS OS TESTES DE INTEGRIDADE PASSARAM COM SUCESSO!")

if __name__ == '__main__':
    test_database()
