import patch_day from './patch-day'
import company from './company'
import project from './project'
import protocol from './protocol'
import technology from './technology'
import user from './user'

const REPO = Object.assign(
  {},
  patch_day,
  company,
  project,
  protocol,
  technology,
  user
)

export default REPO