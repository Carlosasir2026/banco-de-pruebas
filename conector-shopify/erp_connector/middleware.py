import time
import json
import logging

logger = logging.getLogger("django")

class LogAllRequestsMiddleware:
    def __init__(self, get_response):
        self.get_response = get_response

    def __call__(self, request):
        start = time.time()

        # ===== REQUEST =====
        method = request.method
        path = request.get_full_path()

        headers = {
            k: v for k, v in request.headers.items()
        }

        try:
            body = request.body.decode("utf-8")
            try:
                body = json.loads(body)
            except Exception:
                pass
        except Exception:
            body = "<no body>"

        logger.info("⬇️ REQUEST")
        logger.info(f"{method} {path}")
        logger.info(f"Headers: {headers}")
        logger.info(f"Body: {body}")

        # Ejecutar vista
        response = self.get_response(request)

        duration = round((time.time() - start) * 1000, 2)

        # ===== RESPONSE =====
        logger.info("⬆️ RESPONSE")
        logger.info(f"Status: {response.status_code}")
        logger.info(f"Time: {duration} ms")

        try:
            logger.info(f"Response headers: {dict(response.items())}")
        except Exception:
            pass

        return response
