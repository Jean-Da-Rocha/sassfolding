export type UseTableSelectionReturn<T extends Record<string, any>> = {
  readonly clearSelection: () => void;
  readonly handleRowSelection: (rowIndex: number, isSelected: boolean, event?: MouseEvent) => void;
  readonly rowSelection: Ref<Record<string, boolean>>;
  readonly selectedRows: ComputedRef<readonly T[]>;
};
