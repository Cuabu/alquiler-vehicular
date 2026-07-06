import cv2
import json
import os
import requests


def extraer_referencia_de_qr(ruta_imagen: str) -> str:
    """Lee una imagen QR y devuelve el texto o referencia decodificada."""
    if not os.path.exists(ruta_imagen):
        raise FileNotFoundError(f"No se encontró la imagen en: {ruta_imagen}")

    imagen = cv2.imread(ruta_imagen)
    detector = cv2.QRCodeDetector()
    data, bbox, _ = detector.detectAndDecode(imagen)

    if not data:
        raise ValueError("No se pudo detectar ni decodificar ningún código QR en la imagen.")

    print(f"[*] QR decodificado exitosamente. Contenido: {data}")
    return data


def consultar_estado_pago(referencia: str, api_key: str = "TU_API_KEY") -> dict:
    """Consulta el estado del pago en la API (ejemplo adaptado a Nequi Conecta)."""

    # Configuración de la API (Ajusta la URL base según el entorno de pruebas o producción)
    base_url = "https://api.nequi.com/payments/v2"
    endpoint = "/-services-paymentservice-getstatuspayment"
    url = f"{base_url}{endpoint}"

    # Encabezados requeridos por el servicio (Ajusta según tu token de autenticación)
    headers = {
        "Content-Type": "application/json",
        "Accept": "application/json",
        "Authorization": f"Bearer {api_key}",
        # "x-api-key": api_key  # Descomentar si tu pasarela usa este header
    }

    # Cuerpo de la petición con el número o código de referencia
    payload = {
        "RequestMessage": {
            "RequestHeader": {
                "Channel": "PNP04",  # Ejemplo de canal, ajusta según tu integración
                "RequestDate": "2026-07-05T18:00:00Z",
                "MessageID": "1234567890",
            },
            "RequestBody": {
                "any": {"codeQR": referencia}  # O "messageId" / "transactionId"
            },
        }
    }

    print(f"[*] Consultando estado en la API para la referencia: {referencia}...")

    try:
        response = requests.post(
            url, headers=headers, data=json.dumps(payload), timeout=10
        )
        response.raise_for_status()
        return response.json()
    except requests.exceptions.RequestException as e:
        return {"error": True, "detalle": str(e)}


# ==========================================
# EJEMPLO DE USO
# ==========================================
if __name__ == "__main__":
    # CASO 1: Tienes una IMAGEN QR
    print("--- Verificación desde Imagen QR ---")
    ruta_qr = "pago_qr.png"  # Reemplaza con la ruta de tu imagen

    try:
        # 1. Extraer referencia desde el QR
        codigo_referencia = extraer_referencia_de_qr(ruta_qr)

        # 2. Consultar la API con el código extraído
        resultado = consultar_estado_pago(codigo_referencia)
        print("\nResultado de la API:")
        print(json.dumps(resultado, indent=2, ensure_ascii=False))

    except Exception as ex:
        print(f"[!] Error: {ex}")

    print("\n------------------------------------\n")

    # CASO 2: Ya tienes el NÚMERO DE REFERENCIA directamente
    print("--- Verificación desde Número de Referencia ---")
    numero_referencia = "REF-987654321"  # Reemplaza con tu referencia

    resultado_ref = consultar_estado_pago(numero_referencia)
    print("\nResultado de la API:")
    print(json.dumps(resultado_ref, indent=2, ensure_ascii=False))