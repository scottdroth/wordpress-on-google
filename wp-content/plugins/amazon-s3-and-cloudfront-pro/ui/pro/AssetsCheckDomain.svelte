<script>
	import {api, config, state, strings, urls} from "../js/stores";
	import {assetsDomainCheck, currentAssetsSettings} from "./stores";
	import Notification from "../components/Notification.svelte";

	export let domain = "";

	let checking = false;
	let success = false;
	let messageHeading = "Heading";
	let messageBody = "Body";
	let lastChecked = "";

	/**
	 * Check whether the current domain is able to serve assets.
	 *
	 * @returns {Promise<void>}
	 */
	async function checkDomain() {
		checking = true;
		state.pausePeriodicFetch();
		const result = await api.post( 'assets-domain-check', { "domain": domain } );

		if ( result.hasOwnProperty( "success" ) ) {
			config.update( $config => {
				return {
					...$config,
					assets_domain_check: result
				};
			} );
		}

		state.resumePeriodicFetch();
		checking = false;
	}

	/**
	 * If the domain was previously changed but the last check was for another domain,
	 * do another check.
	 *
	 * @param {string} changedDomain
	 *
	 * @returns {boolean}
	 *
	 * This function is used to catch corner cases that could otherwise confuse
	 * the user by displaying outdated or previously hidden notices.
	 */
	function checkChangedDomain( changedDomain ) {
		// Reverted domain.
		if (
			changedDomain === $currentAssetsSettings.domain &&
			$assetsDomainCheck.domain &&
			$assetsDomainCheck.domain !== changedDomain
		) {
			checkDomain();
			return true;
		}

		// Previously empty domain, but domain now set, probably by define.
		if (
			changedDomain.trim().length > 0 &&
			changedDomain === $currentAssetsSettings.domain &&
			$assetsDomainCheck.timestamp &&
			$assetsDomainCheck.domain.trim().length === 0
		) {
			checkDomain();
			return true;
		}

		return false;
	}

	$: checked = checkChangedDomain( domain );
</script>

{#if checking}
	<!-- Domain check in progress -->
	<Notification inline icon="tool-generic-running-animated.svg" heading={$strings.assets_checking}/>
{:else if domain.trim().length === 0}
	<!-- Nothing to check -->
{:else if $assetsDomainCheck.timestamp && domain === $assetsDomainCheck.domain}
	<!-- Check exists for current domain -->
	{#if $assetsDomainCheck.success}
		<Notification inline success icon="notification-success.svg" heading={$assetsDomainCheck.message}>
			<p>
				{@html $assetsDomainCheck.last_checked_message}
				<a href={$urls.settings} on:click|preventDefault={checkDomain}>{@html $strings.assets_check_again}</a>
			</p>
		</Notification>
	{:else}
		<Notification inline error icon="notification-error.svg" heading={$assetsDomainCheck.message}>
			<p>{@html $assetsDomainCheck.error}</p>
			<p>
				{@html $assetsDomainCheck.last_checked_message}
				<a href={$urls.settings} on:click|preventDefault={checkDomain}>{@html $strings.assets_check_again}</a>
			</p>
		</Notification>
	{/if}
{:else}
	<!-- Domain changed since last check -->
	<Notification inline>
		<a href={$urls.settings} on:click|preventDefault={checkDomain}>{@html $strings.assets_check_updated_domain}</a>
	</Notification>
{/if}
