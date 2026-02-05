FROM python:3.12-slim

#Variables de entorno
ENV PYTHONUNBUFFERED=1
ENV PYTHONDONTWRITEBYTECODE=1

#Directorio de trabajo
WORKDIR /app

#Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    gcc \
    python3-dev \
    build-essential \
    postgresql-client \
    && rm -rf /var/lib/apt/lists/*

#Copiar requirements
COPY requirements.txt .
RUN pip install --upgrade pip setuptools wheel
RUN pip install --no-cache-dir -r requirements.txt

#Copiar proyecto
COPY conector-shopify/ .

#Crear directorios necesarios
RUN mkdir -p logs staticfiles media

#Exponer puerto
EXPOSE 8000

#Script de inicio
COPY entrypoint.sh .
RUN chmod +x entrypoint.sh .

ENTRYPOINT ["./entrypoint.sh"]
