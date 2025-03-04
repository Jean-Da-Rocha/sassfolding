export function getInitials(word?: string): string {
  if (!word) {
    return '';
  }

  return word.split(' ').map((word: string): string => word[0].toUpperCase()).join('');
}
