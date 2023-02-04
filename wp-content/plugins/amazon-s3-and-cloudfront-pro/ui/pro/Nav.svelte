<script>
	import {fade} from "svelte/transition";
	import {link} from "svelte-spa-router";
	import {urls} from "../js/stores";
	import {running, tools} from "./stores";
	import Nav from "../components/Nav.svelte";

	/**
	 * Returns the currently running tool's details.
	 *
	 * @param {Object} tools
	 * @param {string} running
	 *
	 * @return {unknown}
	 */
	function runningTool( tools, running ) {
		return Object.values( tools ).filter( ( tool ) => tool.id === running ).shift();
	}

	$: isRunning = !!$running;
	$: tool = runningTool( $tools, $running );
	$: icon = tools.icon( tool, isRunning, true );
</script>

<Nav>
	{#if isRunning}
		<a href="/tools" id="global-animation-wrapper" use:link>
			<div id="animation-running" transition:fade>
				<p class="percentage">{tool.progress}<span>%</span></p>
				<img src="{$urls.assets + 'img/icon/' + icon}" alt="{tool.status_description}"/>
			</div>
		</a>
	{/if}
</Nav>
