import React from 'react';
import { render } from '@testing-library/react';
import Search from './Search';

test('renders Search button', () => {
  const { getByText } = render(<Search />);
  const searchButton = getByText(/Search/i);
  expect(searchButton).toBeInTheDocument();
});
