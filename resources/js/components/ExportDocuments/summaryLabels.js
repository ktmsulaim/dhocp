import { GROUPING_OPTIONS, NAMING_OPTIONS, SEPARATOR_OPTIONS } from "./exportOptions";

function optionLabel(options, value) {
  const option = options.find((opt) => opt.value === value);
  return option ? option.label : "";
}

export function studentsLabel(students, batches) {
  if (students === "active") return "Active students";
  if (students === "all") return "All students";
  const batch = (batches || []).find((b) => String(b.id) === String(students));
  return batch ? batch.name : "Selected batch";
}

export function documentsLabel(includePhoto, itemCount) {
  const types = `${itemCount} document type${itemCount === 1 ? "" : "s"}`;
  if (includePhoto && itemCount > 0) return `Profile photos + ${types}`;
  if (includePhoto) return "Profile photos";
  return itemCount > 0 ? types : "None selected";
}

export function buildSummaryLabels(state) {
  const naming = optionLabel(NAMING_OPTIONS, state.namingFormat);
  const separator = optionLabel(SEPARATOR_OPTIONS, state.separator);
  return {
    studentsLabel: studentsLabel(state.students, state.batches),
    documentsLabel: documentsLabel(state.includePhoto, state.selectedItems.length),
    groupingLabel: optionLabel(GROUPING_OPTIONS, state.groupBy),
    namingLabel: `${naming} / ${separator}`,
  };
}
