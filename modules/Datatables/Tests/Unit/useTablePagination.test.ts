import { describe, expect, it, vi } from 'vitest';

const { reload } = vi.hoisted(() => ({ reload: vi.fn() }));

vi.mock('hybridly', async importOriginal => ({
  ...(await importOriginal<typeof import('hybridly')>()),
  router: { reload },
}));

function createDatatable(perPage: number, to = vi.fn()) {
  return {
    paginator: {
      meta: {
        current_page: 2,
        per_page: perPage,
        total: 120,
      },
      to,
    },
  } as unknown as Datatable<Record<string, any>>;
}

describe('useTablePagination', () => {
  it('offers the default page sizes', () => {
    const { perPageItems } = useTablePagination(createDatatable(10));

    expect(perPageItems.value.map(item => item.label)).toEqual(['10', '25', '50', '100']);
  });

  it('accepts custom page sizes', () => {
    const { perPageItems } = useTablePagination(createDatatable(10), { perPageOptions: [5, 15] });

    expect(perPageItems.value.map(item => item.label)).toEqual(['5', '15']);
  });

  it('highlights the page size currently in use', () => {
    const { perPageItems } = useTablePagination(createDatatable(25));

    expect(perPageItems.value.map(item => item.class)).toEqual([
      undefined,
      'bg-primary text-inverted',
      undefined,
      undefined,
    ]);
  });

  it('goes back to the first page when the page size changes', () => {
    reload.mockClear();
    const { changePerPage } = useTablePagination(createDatatable(10));

    changePerPage(50);

    expect(reload).toHaveBeenCalledWith({
      transformUrl: {
        query: {
          page: 1,
          per_page: 50,
        },
      },
    });
  });

  it('delegates page navigation to the paginator', () => {
    const to = vi.fn();
    const { goToPage } = useTablePagination(createDatatable(10, to));

    goToPage(3);

    expect(to).toHaveBeenCalledWith(3);
  });
});
