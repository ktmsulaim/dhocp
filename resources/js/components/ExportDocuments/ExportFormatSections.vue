<template>
  <div>
    <export-section
      :step="3"
      title="Folder structure"
      subtitle="How files are organised inside the archive"
    >
      <option-tile-group
        :value="groupBy"
        :options="groupingOptions"
        @input="$emit('update:groupBy', $event)"
      />
    </export-section>
    <export-section :step="4" title="File naming" subtitle="How each file is named">
      <option-tile-group
        :value="namingFormat"
        :options="namingOptions"
        code-hint
        @input="$emit('update:namingFormat', $event)"
      />
      <separator-toggle
        :value="separator"
        @input="$emit('update:separator', $event)"
      />
    </export-section>
  </div>
</template>

<script>
import ExportSection from "./ExportSection";
import OptionTileGroup from "./OptionTileGroup";
import SeparatorToggle from "./SeparatorToggle";
import { GROUPING_OPTIONS, NAMING_OPTIONS } from "./exportOptions";

export default {
  name: "export-format-sections",
  components: {
    ExportSection,
    OptionTileGroup,
    SeparatorToggle,
  },
  props: {
    groupBy: {
      type: String,
      required: true,
    },
    namingFormat: {
      type: String,
      required: true,
    },
    separator: {
      type: String,
      required: true,
    },
  },
  computed: {
    groupingOptions() {
      return GROUPING_OPTIONS.map((opt) => ({ ...opt, hint: opt.desc }));
    },
    namingOptions() {
      return NAMING_OPTIONS.map((opt) => ({ ...opt, hint: opt.example }));
    },
  },
};
</script>
