import { ChangeEvent } from 'react'
import { Checkbox, TableRow } from '@mui/material'
import {
  BaseTableCell,
  StickyTableCell,
} from '~/components/organisms/CustomTable/CustomTable.styled'
import {
  ITableHeader,
  ITableHeaderSticky,
  ITableRow,
} from '~/types/customTables'
import { handleSingleRow, manageStickyHeaders } from '../CustomTable.service'
import EditableContent from '../CustomTableCell/EditableContent'
import NonEditableContent from '../CustomTableCell/NonEditableContent'
import { nonStickyStyle, selectionStyle, stickyStyle } from './Row.styled'

interface IProps {
  tableRow: ITableRow
  updateRow: (row: ITableRow) => void
  tableHeaders: ITableHeader[]
  withSelection: boolean
  selectedRows: (string | number)[]
  setSelectedRows: (arr: (string | number)[]) => void
  cSSLeftValuesIterator: IterableIterator<[number, number]>
  isHorizontalOverflow: boolean
  shadow: boolean
}

function NonDraggableRow(props: IProps): JSX.Element {
  const {
    tableRow,
    updateRow,
    tableHeaders,
    selectedRows,
    setSelectedRows,
    withSelection,
    cSSLeftValuesIterator,
    isHorizontalOverflow,
    shadow,
  } = props

  const stickyHeaders: ITableHeaderSticky[] = manageStickyHeaders(tableHeaders)
  const nonStickyHeaders = tableHeaders.filter((header) => !header.sticky)

  return (
    <TableRow key={tableRow.id}>
      {Boolean(withSelection) && (
        <StickyTableCell
          sx={selectionStyle(
            isHorizontalOverflow,
            cSSLeftValuesIterator.next().value[1],
            shadow,
            stickyHeaders.length
          )}
        >
          <Checkbox
            checked={selectedRows ? selectedRows.includes(tableRow.id) : false}
            onChange={(value: ChangeEvent<HTMLInputElement>): void =>
              handleSingleRow(value, tableRow.id, setSelectedRows, selectedRows)
            }
          />
        </StickyTableCell>
      )}

      {stickyHeaders.map((stickyHeader) => (
        <StickyTableCell
          key={stickyHeader.field}
          sx={stickyStyle(
            cSSLeftValuesIterator.next().value[1],
            shadow,
            stickyHeader.isLastSticky,
            stickyHeader.type
          )}
        >
          {stickyHeader.editable ? (
            <EditableContent
              header={stickyHeader}
              row={tableRow}
              onRowUpdate={updateRow}
            />
          ) : null}
          {!stickyHeader.editable && (
            <NonEditableContent header={stickyHeader} row={tableRow} />
          )}
        </StickyTableCell>
      ))}

      {nonStickyHeaders.map((header) => (
        <BaseTableCell sx={nonStickyStyle(header.type)} key={header.field}>
          {header.editable ? (
            <EditableContent
              header={header}
              row={tableRow}
              onRowUpdate={updateRow}
            />
          ) : null}
          {!header.editable && (
            <NonEditableContent header={header} row={tableRow} />
          )}
        </BaseTableCell>
      ))}
    </TableRow>
  )
}

export default NonDraggableRow