const FILENAME_PATTERN = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;

export function filenameFromDisposition(headers, fallback) {
  const disposition =
    headers && (headers["content-disposition"] || headers["Content-Disposition"]);
  if (!disposition) return fallback;

  const matches = FILENAME_PATTERN.exec(disposition);
  if (matches && matches[1]) {
    return matches[1].replace(/['"]/g, "").trim();
  }
  return fallback;
}

export function blobErrorMessage(err, fallback) {
  const data = err && err.response && err.response.data;
  if (!(data instanceof Blob)) return Promise.resolve(fallback);

  return new Promise((resolve) => {
    const reader = new FileReader();
    reader.onload = () => {
      try {
        const parsed = JSON.parse(reader.result);
        resolve(parsed.message || fallback);
      } catch (e) {
        resolve(fallback);
      }
    };
    reader.onerror = () => resolve(fallback);
    reader.readAsText(data);
  });
}

export function downloadBlob({ url, data, fallbackName }) {
  return axios({
    method: "POST",
    url,
    data,
    responseType: "blob",
  }).then((resp) => {
    FileDownload(resp.data, filenameFromDisposition(resp.headers, fallbackName));
    return resp;
  });
}

function appendHiddenInput(form, name, value) {
  if (value === null || value === undefined) return;
  const input = document.createElement("input");
  input.type = "hidden";
  input.name = name;
  input.value = typeof value === "boolean" ? (value ? "1" : "0") : String(value);
  form.appendChild(input);
}

export function submitDownloadForm(url, data) {
  const form = document.createElement("form");
  form.method = "POST";
  form.action = url;
  form.style.display = "none";

  const token = document.querySelector("meta[name='csrf-token']");
  if (token) appendHiddenInput(form, "_token", token.getAttribute("content"));

  Object.keys(data || {}).forEach((name) => {
    const value = data[name];
    if (Array.isArray(value)) {
      value.forEach((item) => appendHiddenInput(form, `${name}[]`, item));
    } else {
      appendHiddenInput(form, name, value);
    }
  });

  document.body.appendChild(form);
  form.submit();
  document.body.removeChild(form);
}

const KB = 1024;
const MB = KB * 1024;
const GB = MB * 1024;

export function formatBytes(bytes) {
  const value = Number(bytes) || 0;
  if (value < KB) return `${value} B`;
  if (value < MB) return `${Math.round(value / KB)} KB`;
  if (value < GB) return `${(value / MB).toFixed(1)} MB`;
  return `${(value / GB).toFixed(1)} GB`;
}
