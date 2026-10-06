export function collectItemIds(modules) {
  const ids = [];
  (modules || []).forEach((mod) => {
    (mod.items || []).forEach((item) => ids.push(item.id));
  });
  return ids;
}
