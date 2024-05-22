export async function getCSRFToken() {
  const response = await fetch("/api/csrf-end-point");
  const data = await response.json();
  let token = data.csrf_token;
  if (token) {
    console.log("Token", token)
      return token;
  }

  const meta = document.querySelector('meta[name="csrf_token"]');
  if (meta) {
      token = meta.getAttribute("content");
  }

  return token;
}

window.getCSRFToken = getCSRFToken;