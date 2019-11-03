<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Working\WorkingSubject\Def;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
final class Definitions
{
	const WORKING_TYPE = "working.def";

	// Abbreviation:
	// WC: Working Continuation
	// WA: Working Action (same as 'transition' in State Machine)
	// WP: Working Place (same as 'state' in State Machine)

	// Definitions of Continuation
	const WC_ADVANCE = "advance";
	const WC_SOURCE = "source";
	const WC_PIPE = "pipe";
	
	// Definitions of Actions
	const WA_DEFINE = "define";
	const WA_IMPORT = "import";
	const WA_PREPARE = "prepare";
	const WA_APPROVE ="approve";
	const WA_PUBLISH ="publish";
	const WA_ADVANCE ="advance";
	const WA_SUSPEND ="suspend";
	const WA_RESUME ="resume";
	const WA_CANCEL ="cancel";
	const WA_TERMINATE ="terminate";
	const WA_ABORT ="abort";
	const WA_FINALIZE ="finalize";
	const WA_ARCHIVE ="archive";
	
	// Definitions of Places
	const WP_NEW = "new";
	const WP_DEFINED = "defined";
	const WP_PREPARING = "preparing";
	const WP_PREPARED = "prepared";
	const WP_PUBLISHED = "published";
	const WP_ADVANCED = "advanced";
	const WP_SUSPENDED = "suspended";
	const WP_CANCELLED = "cancelled";
	const WP_TERMINATED = "terminated";
	const WP_ABORTED = "aborted";
	const WP_FINALIZED = "finalized";
	const WP_ARCHIVED = "archived";
}
