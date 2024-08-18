export function useInitials(word?: string): { initials: string } {
  if (!word) {
    return { initials: '' };
  }

  const initials: string = word.split(' ').map((word: string): string => word[0].toUpperCase()).join('');

  return { initials };
}
