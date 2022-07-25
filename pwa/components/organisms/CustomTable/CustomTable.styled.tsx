import { Table, TableCell, TableContainer } from '@mui/material'
import { styled } from '@mui/system'
import { IStickyBorderStyle } from '~/types'

export const TableContainerWithCustomScrollbar = styled(TableContainer)(
  ({ theme }) => ({
    '&::-webkit-scrollbar': {
      position: 'sticky',
      bottom: '150px',
      height: '4px',
    },
    '&::-webkit-scrollbar-track': {
      backgroundColor: `${theme.palette.colors.white}`,
      top: '15px',
    },
    '&::-webkit-scrollbar-thumb': {
      backgroundColor: `${theme.palette.neutral.main}`,
      borderRadius: '15px',
      top: '15px',
    },
  })
)

export const StyledTable = styled(Table)({
  tableLayout: 'auto',
  width: '100%',
  height: '100%',
  borderCollapse: 'separate',
})

export const BaseTableCell = styled(TableCell)({
  height: '48px',
  maxHeight: '80px',
  maxWidth: '200px',
  textOverflow: 'ellipsis',
  overflow: 'hidden',
})

export const StickyTableCell = styled(BaseTableCell)({
  position: 'sticky',
  left: 0,
  padding: 0,
  height: '100%',
  '&:last-of-type': {
    borderRight: '2px solid',
    borderRightColor: 'colors.neutral.600',
  },
})

export function stickyBorderStyle(shadow: boolean): IStickyBorderStyle {
  return {
    borderBottomColor: 'colors.neutral.300',
    borderRight: '2px solid',
    borderRightColor: 'colors.neutral.600',
    ...(shadow && {
      boxShadow: '5px 0 460px -10px',
      clipPath: 'inset(0px -15px 0px 0px)',
    }),
  }
}