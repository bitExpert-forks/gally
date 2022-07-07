import { forwardRef } from 'react'
import { selectUnstyledClasses } from '@mui/base/SelectUnstyled'
import PopperUnstyled from '@mui/base/PopperUnstyled'
import { styled } from '@mui/system'
import IonIcon from '~/components/atoms/IonIcon/IonIcon'

const ButtonWithIcon = forwardRef<HTMLButtonElement>(function ButtonWithIcon(
  props,
  ref
) {
  return (
    <button {...props} ref={ref}>
      {props.children}
      <IonIcon name="chevron-down" />
    </button>
  )
})

export const StyledButton = styled(ButtonWithIcon)(({ theme }) => ({
  fontFamily: 'Inter',
  padding: '10px 16px',
  background: theme.palette.colors.white,
  width: 180,
  height: 40,
  borderColor: theme.palette.colors.neutral['300'],
  borderStyle: 'solid',
  borderWidth: 1,
  borderRadius: 8,
  fontWeight: 400,
  fontSize: 14,
  lineHeight: '20px',
  color: theme.palette.colors.neutral['600'],
  textAlign: 'left',
  transition: 'border-color 0.3s linear',
  'label + &': {
    marginTop: theme.spacing(3),
  },
  '& ion-icon': {
    float: 'right',
  },
  '&:hover': {
    borderColor: theme.palette.colors.neutral['400'],
  },
  '&:focus, &:focus-within': {
    borderColor: theme.palette.colors.neutral['600'],
  },
  '&.Mui-disabled': {
    borderColor: theme.palette.colors.neutral['300'],
    background: theme.palette.colors.neutral['300'],
    '& ion-icon': {
      color: theme.palette.colors.neutral['400'],
    },
  },

  [`&.${selectUnstyledClasses.focusVisible}`]: {
    outline: '3px solid pink',
  },

  [`&.${selectUnstyledClasses.expanded}`]: {
    borderColor: theme.palette.colors.neutral['600'],
    '& ion-icon': {
      transform: 'rotate(180deg)',
    },
  },
}))

export const StyledListbox = styled('ul')(({ theme }) => ({
  position: 'relative',
  padding: 0,
  background: theme.palette.colors.white,
  width: 180,
  border: '1px solid ' + theme.palette.colors.neutral['300'],
  borderRadius: 8,
  overflow: 'auto',
  boxSizing: 'border-box',
  margin: '4px 0',
  outline: 0,
}))

export const StyledPopper = styled(PopperUnstyled)(() => ({
  zIndex: 1,
}))
