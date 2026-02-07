export function useTableColumns<T extends Record<string, any>>(
  config: ColumnGeneratorConfig<T>,
  components: ResolvedComponents,
): UseTableColumnsReturn<T> {
  const { datatable, getRowActions, handleRowSelection, hasInlineActions, selectable } = config;
  const { UButton, UCheckbox, UDropdownMenu } = components;

  const columns = computed<ColumnDef<T>[]>(() => {
    const result: ColumnDef<T>[] = [];

    if (selectable) {
      result.push(createSelectionColumn());
    }

    datatable.columns.forEach((column: HybridlyTableColumn) => {
      result.push(createDataColumn(column));
    });

    if (hasInlineActions) {
      result.push(createActionsColumn());
    }

    return result;
  });

  function createSelectionColumn(): ColumnDef<T> {
    return {
      cell: ({ row }: TableRowContext<T>) => h(UCheckbox, {
        modelValue: row.getIsSelected(),
        onClick: (event: MouseEvent) => {
          event.preventDefault();

          handleRowSelection(row.index, !row.getIsSelected(), event);
        },
      }),
      header: ({ table }: TableHeaderContext) => h(UCheckbox, {
        'indeterminate': table.getIsSomePageRowsSelected(),
        'modelValue': table.getIsAllPageRowsSelected(),
        'onUpdate:modelValue': (value: boolean) => table.toggleAllPageRowsSelected(value),
      }),
      id: 'select',
      meta: { class: { td: 'w-[50px]' } },
    } as ColumnDef<T>;
  }

  function createDataColumn(column: HybridlyTableColumn): ColumnDef<T> {
    const isSortable = column.isSortable ?? false;
    const columnName = String(column.name);

    return {
      accessorKey: columnName,
      enableSorting: isSortable,
      header: isSortable
        ? () => h(UButton, {
            'class': '-mx-2.5',
            'color': 'neutral',
            'label': column.label,
            'onClick': () => column.toggleSort({
              direction: column.isSorting('asc') ? 'desc' : 'asc',
            }),
            'trailing-icon': getSortIcon(column),
            'variant': 'ghost',
          })
        : column.label,
    } as ColumnDef<T>;
  }

  function getSortIcon(column: HybridlyTableColumn): string | undefined {
    if (column.isSorting('asc')) {
      return 'i-lucide-arrow-up-narrow-wide';
    }

    if (column.isSorting('desc')) {
      return 'i-lucide-arrow-down-wide-narrow';
    }

    return undefined;
  }

  function createActionsColumn(): ColumnDef<T> {
    return {
      cell: ({ row }: TableRowContext<T>) => h(UDropdownMenu, {
        items: getRowActions(row.original),
      }, () => h(UButton, {
        color: 'neutral',
        icon: 'i-lucide-ellipsis-vertical',
        variant: 'ghost',
      })),
      header: '',
      id: 'actions',
      meta: { class: { td: 'w-[50px]' } },
    } as ColumnDef<T>;
  }

  return { columns };
}
