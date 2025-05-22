import re
import os
import argparse

def eliminar_campos_insert(ruta_archivo_entrada, ruta_archivo_salida, campos_a_eliminar):
    """
    Procesa un archivo SQL, eliminando campos específicos de las sentencias INSERT.
    
    Args:
        ruta_archivo_entrada: Ruta al archivo SQL original
        ruta_archivo_salida: Ruta donde se guardará el archivo SQL modificado
        campos_a_eliminar: Lista de nombres de campos que se deben eliminar
    """
    # Leer el archivo SQL de entrada
    with open(ruta_archivo_entrada, 'r', encoding='utf-8') as file:
        contenido = file.read()
    
    # Patrón para identificar sentencias INSERT
    patron_insert = r'INSERT\s+INTO\s+[`"]?(\w+)[`"]?\s*\((.*?)\)\s*VALUES\s*\((.*?)\);'
    
    # Función para procesar cada coincidencia
    def procesar_insert(match):
        tabla = match.group(1)
        columnas_str = match.group(2)
        valores_str = match.group(3)
        
        # Dividir las columnas respetando backticks o comillas
        columnas = []
        columna_actual = ""
        en_comillas = False
        comilla_tipo = None
        
        for char in columnas_str:
            if char in ["`", '"', "'"]:
                en_comillas = not en_comillas if (not comilla_tipo or char == comilla_tipo) else en_comillas
                comilla_tipo = char if en_comillas else None
                columna_actual += char
            elif char == ',' and not en_comillas:
                columnas.append(columna_actual.strip())
                columna_actual = ""
            else:
                columna_actual += char
        
        if columna_actual:
            columnas.append(columna_actual.strip())
        
        # Dividir los valores respetando las comillas
        valores = []
        valor_actual = ""
        en_comillas = False
        comilla_tipo = None
        nivel_parentesis = 0
        
        for char in valores_str:
            if char in ["'", '"'] and (not en_comillas or char == comilla_tipo) and nivel_parentesis == 0:
                en_comillas = not en_comillas
                if en_comillas:
                    comilla_tipo = char
                else:
                    comilla_tipo = None
                valor_actual += char
            elif char == '(' and not en_comillas:
                nivel_parentesis += 1
                valor_actual += char
            elif char == ')' and not en_comillas:
                nivel_parentesis -= 1
                valor_actual += char
            elif char == ',' and not en_comillas and nivel_parentesis == 0:
                valores.append(valor_actual.strip())
                valor_actual = ""
            else:
                valor_actual += char
        
        if valor_actual:
            valores.append(valor_actual.strip())
        
        # Verificar que tenemos el mismo número de columnas y valores
        if len(columnas) != len(valores):
            print(f"Advertencia: Número de columnas ({len(columnas)}) no coincide con número de valores ({len(valores)}) en: {match.group(0)[:100]}...")
            return match.group(0)  # Devolver el original si hay un problema
        
        # Crear pares de columna-valor
        pares = list(zip(columnas, valores))
        
        # Filtrar los pares para eliminar los campos especificados
        nuevos_pares = []
        campos_eliminados = []
        for columna, valor in pares:
            # Extraer el nombre sin backticks o comillas para comparar
            nombre_columna = re.sub(r'[`"\']', '', columna).strip().lower()
            if nombre_columna not in campos_a_eliminar:
                nuevos_pares.append((columna, valor))
            else:
                campos_eliminados.append(nombre_columna)
        
        if campos_eliminados:
            print(f"Eliminados campos {', '.join(campos_eliminados)} de INSERT en tabla {tabla}")
        
        # Reconstruir la sentencia INSERT
        nuevas_columnas = ', '.join(col for col, _ in nuevos_pares)
        nuevos_valores = ', '.join(val for _, val in nuevos_pares)
        
        return f"INSERT INTO {tabla} ({nuevas_columnas}) VALUES ({nuevos_valores});"
    
    # Reemplazar todas las sentencias INSERT en el contenido
    contenido_modificado = re.sub(patron_insert, procesar_insert, contenido, flags=re.IGNORECASE | re.DOTALL)
    
    # Escribir el resultado en el archivo de salida
    with open(ruta_archivo_salida, 'w', encoding='utf-8') as file:
        file.write(contenido_modificado)
    
    return True

if __name__ == "__main__":
    parser = argparse.ArgumentParser(description='Eliminar campos específicos de sentencias INSERT en un archivo SQL.')
    parser.add_argument('entrada', help='Ruta del archivo SQL de entrada')
    parser.add_argument('--salida', help='Ruta del archivo SQL de salida. Si no se proporciona, se utilizará el nombre del archivo de entrada con el sufijo "_modificado".')
    parser.add_argument('--campos', nargs='+', default=[
        'web_decsai', 
        'enlace_temario', 
        'temario_teoria', 
        'temario_practicas', 
        'bibliografia', 
        'evaluacion', 
        'recomendaciones'
    ], help='Lista de campos a eliminar (por defecto son los campos especificados en el script)')
    
    args = parser.parse_args()
    
    ruta_entrada = args.entrada
    campos_a_eliminar = [campo.lower() for campo in args.campos]
    
    if args.salida:
        ruta_salida = args.salida
    else:
        nombre_base, extension = os.path.splitext(ruta_entrada)
        ruta_salida = f"{nombre_base}_modificado{extension}"
    
    if eliminar_campos_insert(ruta_entrada, ruta_salida, campos_a_eliminar):
        print(f"Archivo procesado correctamente. Resultado guardado en: {ruta_salida}")
    else:
        print("Error al procesar el archivo.")