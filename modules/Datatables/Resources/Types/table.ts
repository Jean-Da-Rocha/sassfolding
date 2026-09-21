/**
 * The object returned by Hybridly's `useTable`, for the given record type.
 *
 * Composables take this rather than the raw `useTable` return type, because
 * `ReturnType<typeof useTable>` produces conditional types that do not resolve
 * across generic component boundaries.
 */
export type Datatable<T extends Record<string, any>> = UseTableReturn<Table<T>>;
