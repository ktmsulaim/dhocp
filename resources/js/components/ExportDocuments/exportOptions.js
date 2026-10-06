export const GROUPING_OPTIONS = [
  {
    value: "student_module",
    label: "Student > Module",
    desc: "14230 > Certificates > Degree.png",
  },
  {
    value: "student",
    label: "Student Only",
    desc: "14230 > Degree.png, Photo.jpg",
  },
  {
    value: "flat",
    label: "None (Flat in Root)",
    desc: "All files directly in zip root",
  },
  {
    value: "student_module_field",
    label: "Student > Module > Field",
    desc: "14230 > Certificates > Degree > file",
  },
  {
    value: "module_student",
    label: "Module > Student",
    desc: "Certificates > 14230 > Degree.png",
  },
  {
    value: "module_field",
    label: "Module > Field",
    desc: "Certificates > Degree > 14230.png",
  },
];

export const NAMING_OPTIONS = [
  {
    value: "enroll_document",
    label: "Enroll No + Document Type",
    example: "14230_Degree_Certificate.pdf",
  },
  {
    value: "enroll_only",
    label: "Enrollment Number Only",
    example: "14230.pdf",
  },
  {
    value: "document_only",
    label: "Document Type Only",
    example: "Degree_Certificate.pdf",
  },
  {
    value: "enroll_name_document",
    label: "Enroll + Name + Document",
    example: "14230_John_Doe_Degree.pdf",
  },
];

export const SEPARATOR_OPTIONS = [
  { value: "underscore", char: "_", label: "Underscore (_)" },
  { value: "hyphen", char: "-", label: "Hyphen (-)" },
  { value: "spaced_dash", char: " - ", label: "Spaced dash ( - )" },
];

export function separatorChar(key) {
  const option = SEPARATOR_OPTIONS.find((opt) => opt.value === key);
  return option ? option.char : "_";
}
