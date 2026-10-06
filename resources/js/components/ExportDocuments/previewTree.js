import { separatorChar } from "./exportOptions";

const ENROLL = "14230";
const STUDENT_NAME = "John_Doe";
const MODULE_FOLDER = "Certificates";
const FIELD_FOLDER = "Degree Certificate";
const PROFILE_FOLDER = "Profile";
const SUMMARY_FILE = "export_summary.txt";

function sampleName(docType, ext, format, sep, enrollOnlyName) {
  if (format === "enroll_only") return enrollOnlyName || `${ENROLL}.${ext}`;
  if (format === "document_only") return `${docType}.${ext}`;
  if (format === "enroll_name_document") {
    return `${ENROLL}${sep}${STUDENT_NAME}${sep}${docType}.${ext}`;
  }
  return `${ENROLL}${sep}${docType}.${ext}`;
}

function sampleFiles({ groupBy, namingFormat, separator }) {
  const sep = separatorChar(separator);
  const collisionName = groupBy === "flat" ? `${ENROLL}_1.jpg` : `${ENROLL}.jpg`;
  return {
    photo: sampleName("Photo", "jpg", namingFormat, sep),
    doc: sampleName("Degree_Certificate", "pdf", namingFormat, sep),
    doc2: sampleName("Passport_Photo", "jpg", namingFormat, sep, collisionName),
  };
}

function folder(name, children) {
  return { name: `${name}/`, children: children.filter(Boolean) };
}

function file(name) {
  return { name, children: [] };
}

function rootNodes(groupBy, includePhoto, files) {
  const photo = includePhoto ? file(files.photo) : null;
  const doc = file(files.doc);
  const doc2 = file(files.doc2);

  switch (groupBy) {
    case "flat":
      return [photo, doc, doc2];
    case "student":
      return [folder(ENROLL, [photo, doc, doc2])];
    case "student_module":
      return [
        folder(ENROLL, [
          photo ? folder(PROFILE_FOLDER, [photo]) : null,
          folder(MODULE_FOLDER, [doc, doc2]),
        ]),
      ];
    case "student_module_field":
      return [folder(ENROLL, [folder(MODULE_FOLDER, [folder(FIELD_FOLDER, [doc])])])];
    case "module_student":
      return [folder(MODULE_FOLDER, [folder(ENROLL, [doc, doc2])])];
    case "module_field":
      return [folder(MODULE_FOLDER, [folder(FIELD_FOLDER, [doc])])];
    default:
      return [];
  }
}

function renderChildren(children, prefix, lines) {
  children.forEach((child, index) => {
    const isLast = index === children.length - 1;
    lines.push(`${prefix}${isLast ? "└── " : "├── "}${child.name}`);
    renderChildren(child.children, `${prefix}${isLast ? "    " : "│   "}`, lines);
  });
}

export function buildPreviewTree({ groupBy, namingFormat, separator, includePhoto }) {
  const files = sampleFiles({ groupBy, namingFormat, separator });
  const nodes = rootNodes(groupBy, includePhoto, files).filter(Boolean);
  const lines = [];

  nodes.forEach((node) => {
    lines.push(node.name);
    renderChildren(node.children, "", lines);
  });
  lines.push(SUMMARY_FILE);

  return lines;
}
