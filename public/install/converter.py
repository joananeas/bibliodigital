import pandas as pd
import os

def xls_to_csv(input_files):
    current_directory = r"/var/www/html/temporal/"
    if not os.path.exists(current_directory):
        # print("No existe el directorio")
        return "error"  # Aquí deberías retornar un error si el directorio no existe

    for file in input_files:
        input_path = os.path.join(current_directory, file)
        output_path = os.path.join(current_directory, os.path.splitext(file)[0] + ".csv")
        df = pd.read_excel(input_path)
        df.to_csv(output_path, index=False)

        # Comprobar que se ha creado el archivo
        if not os.path.exists(output_path):
            return "error"  # Si no se crea el archivo, retorna error
        
    return "ok"  # Si todo sale bien, retorna ok

input_files = ["cataleg.xls", "exemplars.xls"]
print(xls_to_csv(input_files))