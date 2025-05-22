import sqlparse
import re
import argparse
import bcrypt
from faker import Faker

def generate_fake_data():
    fake = Faker()
    return {
        "nombre": fake.first_name(),
        "apellidos": fake.last_name() + " " + fake.last_name(),
        "nombre_abreviado": fake.first_name()[0] + ". " + fake.last_name(),
        "dni_pasaporte": fake.unique.random_number(digits=8, fix_len=True),
        "correo": fake.email(),
        "telefono": fake.phone_number(),
        "telefono_despacho": fake.phone_number(),
        "login": fake.user_name(),
        "passwd": bcrypt.hashpw(fake.password().encode(), bcrypt.gensalt()).decode()
    }

def process_sql_file(input_path, output_path):
    with open(input_path, 'r', encoding='utf-8') as f:
        sql_content = f.read()
    
    parsed_statements = sqlparse.parse(sql_content)
    modified_sql = ""
    
    for statement in parsed_statements:
        if statement.get_type() == "INSERT":
            fake_data = generate_fake_data()
            modified_sql += modify_insert_statement(str(statement), fake_data) + "\n" 
        else:
            modified_sql += str(statement) + "\n"
    
    with open(output_path, 'w', encoding='utf-8') as f:
        f.write(modified_sql)

def modify_insert_statement(insert_statement, fake_data):
    pattern = re.compile(r'\((.*?)\)\s*VALUES\s*\((.*?)\);', re.IGNORECASE | re.DOTALL)
    match = pattern.search(insert_statement)
    
    if not match:
        return insert_statement
    
    columns = [col.strip().strip('`"') for col in match.group(1).split(',')]
    values = [val.strip().strip('"\'') for val in match.group(2).split(',')]
    
    new_values = []
    for col, val in zip(columns, values):
        if col == "tipo_usuario" and val.lower() == "becario":
            new_values.append('"Contratado"')
        elif col == "id_despacho" and (val == "" or val == '""' or val == "''"):
            new_values.append('"90"')
        elif col in fake_data:
            new_values.append(f'"{fake_data[col]}"')
        else:
            new_values.append(f'"{val}"')
    
    modified_insert = insert_statement[:match.start(2)] + ', '.join(new_values) + insert_statement[match.end(2):]
    return modified_insert

if __name__ == "__main__":
    parser = argparse.ArgumentParser(description="Sanitiza INSERTs en un archivo SQL")
    parser.add_argument("input_sql", help="Ruta del archivo .sql de entrada")
    parser.add_argument("output_sql", help="Ruta del archivo .sql de salida")
    args = parser.parse_args()
    
    process_sql_file(args.input_sql, args.output_sql)