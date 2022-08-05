import DropDown from './DropDown'
import { renderWithProviders } from '~/services'

describe('DropDown match snapshot', () => {
  it('BadgeDisabledFalse', () => {
    const { container } = renderWithProviders(
      <DropDown
        disabled={false}
        options={[
          { label: 'Ten', value: 10 },
          { label: 'Twenty', value: 20 },
          { label: 'Thirty', value: 30 },
          { label: 'Fourty', value: 40, disabled: true },
        ]}
      />
    )
    expect(container).toMatchSnapshot()
  })

  it('BadgeDisabledTrue', () => {
    const { container } = renderWithProviders(
      <DropDown
        disabled
        options={[
          { label: 'Ten', value: 10 },
          { label: 'Twenty', value: 20 },
          { label: 'Thirty', value: 30 },
          { label: 'Fourty', value: 40 },
        ]}
      />
    )
    expect(container).toMatchSnapshot()
  })

  it('BadgeLabel', () => {
    const { container } = renderWithProviders(
      <DropDown
        label="Label"
        options={[
          { label: 'Ten', value: 10 },
          { label: 'Twenty', value: 20 },
          { label: 'Thirty', value: 30 },
          { label: 'Fourty', value: 40 },
        ]}
      />
    )
    expect(container).toMatchSnapshot()
  })

  it('BadgeRequiredFalse', () => {
    const { container } = renderWithProviders(
      <DropDown
        required={false}
        options={[
          { label: 'Ten', value: 10 },
          { label: 'Twenty', value: 20 },
          { label: 'Thirty', value: 30 },
          { label: 'Fourty', value: 40 },
        ]}
      />
    )
    expect(container).toMatchSnapshot()
  })

  it('BadgeRequiredTrue', () => {
    const { container } = renderWithProviders(
      <DropDown
        required
        options={[
          { label: 'Ten', value: 10 },
          { label: 'Twenty', value: 20 },
          { label: 'Thirty', value: 30 },
          { label: 'Fourty', value: 40 },
        ]}
      />
    )
    expect(container).toMatchSnapshot()
  })

  it('BadgeMultiple', () => {
    const { container } = renderWithProviders(
      <DropDown
        onChange={(): void => {
          Math.floor(1)
        }}
        value={[]}
        multiple
        disabled={false}
        label="Label"
        options={[
          { label: 'Ten', value: 10 },
          { label: 'Twenty', value: 20 },
          { label: 'Thirty', value: 30 },
          { label: 'Fourty', value: 40 },
        ]}
      />
    )
    expect(container).toMatchSnapshot()
  })
})