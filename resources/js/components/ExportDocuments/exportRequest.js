import { submitDownloadForm } from "../downloadUtils";

const DEFAULT_ERROR = "Could not check the selected files. Please try again.";
const NETWORK_ERROR = "Export failed. Please check your network connection.";

function firstValidationError(errors) {
  if (!errors || typeof errors !== "object") return null;
  const first = Object.values(errors)[0];
  return (Array.isArray(first) ? first[0] : first) || null;
}

function summaryError(err) {
  if (!err || !err.response) return new Error(NETWORK_ERROR);

  const data = err.response.data || {};
  const error = new Error(firstValidationError(data.errors) || data.message || DEFAULT_ERROR);
  if (data.files_count !== undefined) error.summary = data;
  return error;
}

export function fetchExportSummary(url, payload) {
  return axios.post(url, payload).then(
    (resp) => resp.data,
    (err) => {
      throw summaryError(err);
    }
  );
}

export function startDocumentsDownload(url, payload) {
  submitDownloadForm(url, payload);
}
