import pandas as pd
import os

def xls_to_csv(input_files, ):
    current_directory = os.path.dirname(os.path.abspath(__file__))
    for input_file in input_files:
        if not input_file.endswith(".xls") or not os.path.exists(os.path.join(current_directory, input_file)):
            raise ValueError(f"El archivo {input_file} no es un .xls o no existe en {current_directory}")

        input_file_path = os.path.join(current_directory, input_file)
        output_file_path = input_file_path.replace(".xls", ".csv")
        
        data = pd.read_excel(input_file_path)
        
        data.to_csv(output_file_path, index=False, header=False, sep=",", encoding="utf-8")

input_files = ["cataleg.xls", "exemplars.xls"]
xls_to_csv(input_files)