import { formatBytes } from "../downloadUtils";
import { fetchExportSummary, startDocumentsDownload } from "./exportRequest";

const LARGE_FILE_COUNT = 1000;
const LARGE_TOTAL_BYTES = 500 * 1024 * 1024;
const LARGE_EXPORT_NOTE =
  "This is a large export and may take several minutes. You can follow its progress in your browser's downloads.";

export function buildExportPayload(state) {
  return {
    students: state.students,
    include_photo: state.includePhoto,
    items: state.selectedItems,
    group_by: state.groupBy,
    naming_format: state.namingFormat,
    separator: state.separator,
  };
}

export function buildDownloadNotice(summary) {
  const parts = [
    "Your download has started.",
    summary.message,
    `Estimated size: ${formatBytes(summary.total_bytes)}.`,
  ];
  if (summary.files_count >= LARGE_FILE_COUNT || summary.total_bytes >= LARGE_TOTAL_BYTES) {
    parts.push(LARGE_EXPORT_NOTE);
  }
  return parts.filter(Boolean).join(" ");
}

export function runDocumentsExport({ summaryUrl, downloadUrl, payload }) {
  return fetchExportSummary(summaryUrl, payload).then((summary) => {
    startDocumentsDownload(downloadUrl, payload);
    return { summary, notice: buildDownloadNotice(summary) };
  });
}
