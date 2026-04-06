export function useDateField(field: Ref<string>) {
  return computed<DateValue | undefined>({
    get: () => {
      if (!field.value) {
        return undefined;
      }

      // Extract YYYY-MM-DD portion — handles both date-only and full datetime strings
      return parseDate(field.value.substring(0, 10));
    },
    set: (value) => {
      field.value = value ? value.toString() : '';
    },
  });
}
