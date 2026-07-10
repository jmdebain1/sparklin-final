/**
 * Vérification reCAPTCHA v3 (score).
 * - Si aucune clé secrète configurée → renvoie true (fail-open, ne bloque pas).
 * - Sinon : token requis, success requis, action vérifiée si fournie,
 *   et score >= seuil (RECAPTCHA_MIN_SCORE, défaut 0.6).
 */
export async function recaptchaVerify(token, expectedAction = "") {
  const secret = process.env.RECAPTCHA_SECRET_KEY || "";
  if (secret === "") return true; // non configuré → on ne bloque pas
  if (!token) return false;

  const min = parseFloat(process.env.RECAPTCHA_MIN_SCORE || "0.6");

  const resp = await fetch("https://www.google.com/recaptcha/api/siteverify", {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body: new URLSearchParams({ secret, response: token }),
  });
  const data = await resp.json().catch(() => ({}));

  if (!data.success) return false;
  if (expectedAction !== "" && data.action !== expectedAction) return false;
  return (parseFloat(data.score) || 0) >= min;
}
