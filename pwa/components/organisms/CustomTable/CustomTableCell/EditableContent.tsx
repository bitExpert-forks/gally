import { Box, Switch } from '@mui/material'
import { DataContentType, IOptions, ITableHeader, ITableRow } from '~/types'

import DropDown from '~/components/atoms/form/DropDown'

import NonEditableContent from './NonEditableContent'

interface IProps {
  header: ITableHeader
  row: ITableRow
  onRowUpdate: (row: ITableRow) => void
}

function EditableContent(props: IProps): JSX.Element {
  const { header, row, onRowUpdate } = props

  function handleDropdownChange(value: number | string): void {
    row[header.field] = value
    onRowUpdate(row)
  }

  function handleSwitchChange(
    value: React.ChangeEvent<HTMLInputElement>
  ): void {
    row[header.field] = value.target.checked
    onRowUpdate(row)
  }

  const defaultOption: IOptions = [
    {
      label: 'no label provided',
      value: -99,
    },
  ]

  function rowDisplayAccordingToType(header: ITableHeader): JSX.Element {
    switch (header.type) {
      case DataContentType.DROPDOWN:
        return (
          <Box>
            <DropDown
              options={header.options ? header.options : defaultOption}
              value={row[header.field] as number}
              onChange={handleDropdownChange}
            />
          </Box>
        )
      case DataContentType.BOOLEAN:
        return (
          <Switch
            onChange={handleSwitchChange}
            value={row[header.field] as boolean}
            checked={row[header.field] as boolean}
          />
        )
      default:
        return <NonEditableContent header={header} row={row} />
    }
  }

  return <>{rowDisplayAccordingToType(header)}</>
}

export default EditableContent
