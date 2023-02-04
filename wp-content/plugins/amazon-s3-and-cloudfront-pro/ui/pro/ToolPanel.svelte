<script>
	import {tweened} from "svelte/motion";
	import {cubicOut} from 'svelte/easing';
	import {Confetti} from "svelte-confetti";
	import {bucket_writable, strings, urls} from "../js/stores";
	import {running, tools, toolsLocked} from "./stores";
	import Panel from "../components/Panel.svelte";
	import PanelRow from "../components/PanelRow.svelte";
	import Button from "../components/Button.svelte";

	export let tool = {};

	// Total processed related variables.
	$: showTotal = tool.hasOwnProperty( "total_progress" );
	$: initial = !!(showTotal && tool.total_progress < 1);
	$: partialComplete = !!(showTotal && tool.total_progress > 0 && tool.total_progress < 100);
	$: complete = !!(showTotal && !initial && !partialComplete);

	// In progress related variables.
	$: isRunning = !!($running && $running === tool.id);
	$: starting = !!(isRunning && tool.progress < 1 && !tool.is_paused);
	// Buttons should be disabled if another tool is running, current tool is in process of pausing or cancelling, or all tools locked.
	$: disabled = ($running && $running !== tool.id) || (tool.is_processing && tool.is_paused) || tool.is_cancelled || $toolsLocked;
	$: disabled_bucket_access = tool.requires_bucket_access && !$bucket_writable;

	let progress = tweened( 0, {
		duration: 400,
		easing: cubicOut
	} );

	/**
	 * Returns the numeric percentage progress for the running job.
	 *
	 * @param {Object} tool
	 * @param {boolean} isRunning
	 *
	 * @return {number}
	 */
	function getProgress( tool, isRunning ) {
		if ( isRunning ) {
			return tool.progress;
		} else if ( showTotal ) {
			return tool.total_progress;
		}

		return 0;
	}

	$: progress.set( getProgress( tool, isRunning ) );

	/**
	 * Returns state dependent icon for tool.
	 *
	 * @param {Object} tool
	 * @param {boolean} isRunning
	 * @return {string}
	 */
	function getIcon( tool, isRunning ) {
		const icon = tools.icon( tool, isRunning, false );

		return $urls.assets + "img/icon/" + icon;
	}

	$: icon = getIcon( tool, isRunning );

	/**
	 * Potentially returns a map of tools that are related to the current tool.
	 *
	 * Map is keyed by tool's id (key), values are tool objects.
	 *
	 * @param {Object} tool
	 *
	 * @return {Map<string, object>}
	 */
	function getRelatedTools( tool ) {
		let related = new Map();

		if ( tool.hasOwnProperty( "related_tools" ) && tool.related_tools.length > 0 ) {
			tool.related_tools.forEach( ( key ) => {
				if ( $tools.hasOwnProperty( key ) ) {
					related.set( key, $tools[ key ] );
				}
			} )
		}

		return related;
	}

	$: relatedTools = getRelatedTools( tool );

	/**
	 * Starts a tool's job.
	 *
	 * @param {Object} tool
	 */
	function handleStartTool( tool ) {
		$running = tool.id;
		tool.is_queued = true;
		tools.start( tool.id );
	}

	/**
	 * Handles a Start button click.
	 */
	function handleStart() {
		handleStartTool( tool );
	}

	/**
	 * Handles a Pause or Resume button click.
	 */
	function handlePauseResume() {
		tool.is_paused = !tool.is_paused;
		tools.pauseResume( tool.id );
	}

	/**
	 * Handles a Cancel button click.
	 */
	function handleCancel() {
		tool.is_cancelled = true;
		tools.cancel( tool.id );
	}
</script>

<Panel multi class="tools-panel">
	<PanelRow header>
		<img src={icon} type="image/svg+xml" alt={tool.title}>
		{#if showTotal}
			{#if initial}
				<h3>{@html tool.title}</h3>
			{:else if partialComplete}
				<h3>{@html tool.title_partial_complete}</h3>
			{:else}
				<h3>{@html tool.title_complete}</h3>
			{/if}
		{:else}
			<h3>{@html tool.title}</h3>
		{/if}
		<div class="buttons-right">
			{#if isRunning}
				<Button outline {disabled} class="pause" on:click={handlePauseResume}>
					{#if tool.is_paused}
						{$strings.resume_button}
					{:else}
						{$strings.pause_button}
					{/if}
				</Button>
				<Button outline {disabled} on:click={handleCancel}>{$strings.cancel_button}</Button>
			{:else}
				{#if complete}
					<span class="emoji-party">🎉</span>
					<Confetti x={[0.3, 1.2]} y={[0.3, 1.2]} rounded cone/>
				{:else if disabled_bucket_access}
					<Button primary disabled={true} title={$strings.disabled_tool_bucket_access}>{@html partialComplete ? tool.button_partial_complete : tool.button}</Button>
				{:else if initial}
					<Button primary {disabled} title={disabled ? $strings.disabled_tool_button : ""} on:click={handleStart}>{@html tool.button}</Button>
				{:else if partialComplete}
					<Button primary {disabled} title={disabled ? $strings.disabled_tool_button : ""} on:click={handleStart}>{@html tool.button_partial_complete}</Button>
				{:else}
					<Button primary {disabled} title={disabled ? $strings.disabled_tool_button : ""} on:click={handleStart}>{@html tool.button}</Button>
				{/if}
			{/if}
		</div>
	</PanelRow>
	{#if complete || partialComplete || isRunning}
		<PanelRow class="body flex-row progress-bar">
			<div class="status">
				{#if isRunning}
					<h4>
						<strong>{getProgress( tool, isRunning )}%</strong> ({tool.queue.processed} / {tool.queue.total})
						{@html tool.status_description ? " " + tool.status_description : " " + tool.busy_description}
					</h4>
				{:else }
					<h4>{@html tool.progress_description}</h4>
				{/if}
				<slot name="status-right" {isRunning}/>
			</div>
			<div
				class="progress"
				class:stripe={isRunning && ! tool.is_paused}
				class:animate={starting}
				title={! isRunning && showTotal ? "(" + tool.total_processed + " / " + tool.total_items + ")" : ""}
			>
				<span class="indicator animate" class:complete class:running={isRunning} style="width: {$progress}%"></span>
			</div>
		</PanelRow>
	{/if}
	{#if !complete && !partialComplete && !isRunning}
		<PanelRow class="body flex-row">
			<p class="desc">{@html tool.more_info}</p>
		</PanelRow>
		{#if !disabled && relatedTools.size > 0 }
			<PanelRow class="body flex-column" footer>
				{#each [...relatedTools] as [key, relatedTool] }
					<p>
						<a
							href={$urls.settings}
							on:click|preventDefault={() => handleStartTool(relatedTool)}
							title={relatedTool.more_info}
						>
							{relatedTool.title}
						</a>
					</p>
				{/each}
			</PanelRow>
		{/if}
	{/if}
</Panel>
