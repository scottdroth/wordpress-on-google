<script>
	import {strings} from "../js/stores";
	import {
		assetsSettings,
		assetsSettingsChanged,
		assetsSettingsLocked,
		currentAssetsSettings,
		enableAssets
	} from "./stores";
	import Page from "../components/Page.svelte";
	import Notifications from "../components/Notifications.svelte";
	import AssetsSettings from "./AssetsSettings.svelte";
	import AssetsUpgrade from "./AssetsUpgrade.svelte";
	import Footer from "../components/Footer.svelte";
	import {setContext} from "svelte";

	export let name = "assets";

	// Let all child components know if settings are currently locked.
	setContext( "settingsLocked", assetsSettingsLocked );
</script>

<Page {name} on:routeEvent initialSettings={currentAssetsSettings}>
	<Notifications tab={name}/>
	<h2 class="page-title">{$strings.assets_title}</h2>
	<div class="assets-page wrapper">
		{#if $enableAssets}
			<AssetsSettings/>

			<div class="notice notice-qsg">
				<p>{@html $strings.assets_quick_start_guide}</p>
			</div>
		{:else}
			<AssetsUpgrade/>
		{/if}
	</div>
</Page>

<Footer settingsStore={assetsSettings} settingsChangedStore={assetsSettingsChanged} on:routeEvent/>
