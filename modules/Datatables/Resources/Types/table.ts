/**
 * `ReturnType<typeof useTable>` produces conditional types that do not resolve across
 * generic component boundaries, so composables take this alias instead.
 */
export type Datatable<T extends Record<string, any>> = UseTableReturn<Table<T>>;
