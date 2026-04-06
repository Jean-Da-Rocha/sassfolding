export function useTableColumns<T extends Record<string, any>>(
  config: ColumnGeneratorConfig,
  components: ResolvedComponents,
): UseTableColumnsReturn<T> {
  const { datatable, getRowActions, handleCheckboxClick, hasInlineActions, selectable } = config;
  const { UButton, UCheckbox, UDropdownMenu } = components;

  const columns = computed<ColumnDef<T>[]>(() => {
    const result: ColumnDef<T>[] = [];

    if (selectable) {
      result.push(buildSelectionColumn());
    }

    datatable.columns.forEach((column: HybridlyTableColumn) => {
      result.push(buildDataColumn(column));
    });

    if (hasInlineActions) {
      result.push(buildActionsColumn());
    }

    return result;
  });

  function buildSelectionColumn(): ColumnDef<T> {
    return {
      cell: ({ row }: TableRowContext<T>) => h('label', {
        class: 'cursor-pointer',
        onClick: (event: MouseEvent) => {
          event.preventDefault();
          handleCheckboxClick(row.index, event);
        },
      }, h(UCheckbox, {
        modelValue: row.getIsSelected(),
      })),
      header: ({ table }: TableHeaderContext) => h(UCheckbox, {
        'indeterminate': table.getIsSomePageRowsSelected(),
        'modelValue': table.getIsAllPageRowsSelected(),
        'onUpdate:modelValue': (value: boolean) => table.toggleAllPageRowsSelected(value),
      }),
      id: 'select',
      meta: { class: { td: 'w-[50px]' } },
    } as ColumnDef<T>;
  }

  function buildDataColumn(hybridlyColumn: HybridlyTableColumn): ColumnDef<T> {
    const columnName = String(hybridlyColumn.name);

    return {
      accessorKey: columnName,
      enableSorting: hybridlyColumn.isSortable,
      header: ({ column }: TableHeaderContext) => {
        const isPinned = column.getIsPinned();

        const sortButton = hybridlyColumn.isSortable
          ? h(UButton, {
              'class': '-mx-2.5',
              'color': 'neutral',
              'label': hybridlyColumn.label,
              'onClick': () => hybridlyColumn.toggleSort({
                direction: hybridlyColumn.isSorting('asc') ? 'desc' : 'asc',
              }),
              'trailing-icon': getSortIcon(hybridlyColumn),
              'variant': 'ghost',
            })
          : h('span', hybridlyColumn.label);

        const pinButton = h(UButton, {
          class: 'ml-auto',
          color: 'neutral',
          icon: isPinned ? 'i-lucide-pin-off' : 'i-lucide-pin',
          onClick: () => column.pin(isPinned ? false : 'left'),
          size: '2xs',
          variant: 'ghost',
        });

        return h('div', { class: 'flex items-center' }, [sortButton, pinButton]);
      },
    } as ColumnDef<T>;
  }

  function buildActionsColumn(): ColumnDef<T> {
    return {
      cell: ({ row }: TableRowContext<T>) => h(UDropdownMenu, {
        items: getRowActions(row.index),
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

function getSortIcon(column: HybridlyTableColumn): string | undefined {
  if (column.isSorting('asc')) {
    return 'i-lucide-arrow-up-narrow-wide';
  }

  if (column.isSorting('desc')) {
    return 'i-lucide-arrow-down-wide-narrow';
  }

  return undefined;
}
